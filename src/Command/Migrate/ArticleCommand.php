<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 12:01
 */

namespace App\Command\Migrate;

use App\Application\Article\Command\AddAttachments;
use App\Application\Article\Command\CreateArticle;
use App\Utils\Text;
use App\Utils\Validation;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Messenger\Exception\ValidationFailedException;

/**
 * Class ArticleCommand
 *
 * @package App\Command\Migrate
 */
class ArticleCommand extends BaseCommand
{
    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * @var string
     */
    private $currentDirectory;

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->fs               = new Filesystem();
        $this->currentDirectory = getcwd();
        $legacyDefaultPath      = $this->fs->makePathRelative(
            __DIR__ . '/../../../../website',
            $this->currentDirectory
        );

        parent::configure();
        $this
            ->setName('app:migrate:article')
            ->setDescription('Migrates news articles from legacy database.')
            ->setHelp('This command allows you to migrate news articles from a IISHF legacy database.')
            ->addOption(
                'legacy-path',
                'p',
                InputOption::VALUE_REQUIRED,
                'Path to legacy website',
                $legacyDefaultPath
            );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $this->io->title('Migrate news articles rom legacy database');

        $legacyPath = $input->getOption('legacy-path');
        $this->io->note('Using legacy website path ' . $legacyPath);
        $legacyPathFull = $legacyPath;
        if ($legacyPathFull[0] !== '/') {
            $legacyPathFull = $this->currentDirectory . '/' . $legacyPathFull;
            $this->io->comment('Full legacy website path ' . $legacyPathFull);
        }

        $articlePath        = $legacyPathFull . '/www/wwwroot/news/articles';
        $migrateAttachments = true;
        if (!realpath($articlePath) || !is_dir($articlePath) || !is_readable($articlePath . '/.')) {
            $this->io->warning('Legacy article path ' . $articlePath . ' does not exist or is not readable.');
            $migrateAttachments = false;
        } else {
            $this->io->comment('Full legacy article path ' . $articlePath);
        }

        $cat1List = $this->createLookupMap('SELECT id, name FROM cat_cat1');
        $cat2List = $this->createLookupMap('SELECT id, name FROM cat_cat2');
        $userList = $this->createLookupMap('SELECT id, userid FROM sys_users', 'id', 'userid');

        $imageList = $this->createAttachmentMap('SELECT nid, filename, caption, mime, type FROM news_images');
        $fileList  = $this->createAttachmentMap('SELECT nid, filename, title, mime FROM news_files');

        $articles = $this->db->fetchAll('SELECT id, uid, title, cat1, cat2, subtitle, contents, edited FROM news');
        $this->io->progressStart(\count($articles));
        $results = [];
        $this->beginTransaction();
        try {
            foreach ($articles as $i => $article) {
                $tags = [];
                if (isset($cat1List[$article['cat1']])) {
                    $tags[] = $cat1List[$article['cat1']];
                }
                if (isset($cat2List[$article['cat2']])) {
                    $tags[] = $cat2List[$article['cat2']];
                }

                $createArticle = CreateArticle::createLegacy($userList[$article['uid']] ?? 'system@iishf.com')
                                              ->setTitle($article['title'])
                                              ->setSubtitle($article['subtitle'])
                                              ->setBody($article['contents'])
                                              ->setTags($tags)
                                              ->setPublishedAt(new \DateTimeImmutable($article['edited']));

                $thisArticlePath = $articlePath . '/article' . $article['id'];

                $addAttachments = AddAttachments::create($createArticle->getId());
                if ($migrateAttachments
                    && file_exists($thisArticlePath)
                    && is_dir($thisArticlePath)
                    && is_readable($thisArticlePath . '/.')
                ) {
                    foreach ($imageList[$article['id']] ?? [] as $image) {
                        $imageFile = self::getFile($thisArticlePath . '/' . $image['filename']);
                        if ($imageFile !== null) {
                            $addAttachments->addImage($image['type'] === 'P', $imageFile, $image['caption']);
                        }
                    }
                    foreach ($fileList[$article['id']] ?? [] as $file) {
                        $fileFile = self::getFile($thisArticlePath . '/' . $file['filename']);
                        if ($fileFile !== null) {
                            $addAttachments->addDocument($fileFile, $file['title']);
                        }
                    }
                }

                $this->beginTransaction();
                try {
                    $this->dispatchCommand($createArticle);
                    if (count($addAttachments) > 0) {
                        $this->dispatchCommand($addAttachments);
                    }
                    $result = '';
                    $this->commitTransaction();
                } catch (ValidationFailedException $e) {
                    $this->rollbackTransaction();
                    $result = implode(PHP_EOL, Validation::getViolations($e));
                } catch (\Throwable $e) {
                    $this->rollbackTransaction();
                    $result = $e->getMessage();
                }

                $results[] = [
                    $i + 1,
                    $createArticle->getPublishedAt()->format('Y-m-d H:i'),
                    Text::shorten($createArticle->getTitle(), 64),
                    empty($tags) ? '-' : implode(', ', $tags),
                    count($addAttachments),
                    $result,
                ];
                $this->io->progressAdvance();

                $this->clearEntityManager();
            }
            $this->commitTransaction();
        } catch (\Exception $e) {
            $this->rollbackTransaction();
            throw $e;
        }
        $this->io->progressFinish();
        $this->io->table(
            ['#', 'Date', 'Title', 'Tags', 'Atch.', 'Err.'],
            $results
        );

        return 0;
    }

    /**
     * @param string $query
     * @param string $keyColumn
     * @param string $valueColumn
     * @return array
     */
    private function createLookupMap(string $query, string $keyColumn = 'id', string $valueColumn = 'name'): array
    {
        $map = [];
        foreach ($this->db->fetchAll($query) as $i) {
            $map[(int)$i[$keyColumn]] = $i[$valueColumn];
        }
        return $map;
    }

    /**
     * @param string $query
     * @param string $keyColumn
     * @return array
     */
    private function createAttachmentMap(string $query, string $keyColumn = 'nid'): array
    {
        $map = [];
        foreach ($this->db->fetchAll($query) as $a) {
            $map[$a[$keyColumn]][] = $a;
        }
        return $map;
    }

    /**
     * @param string $path
     * @return \SplFileInfo|null
     */
    private static function getFile(string $path): ?\SplFileInfo
    {
        if (file_exists($path) && is_file($path) && is_readable($path)) {
            return new \SplFileInfo($path);
        }
        return null;
    }
}
