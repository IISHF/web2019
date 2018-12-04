<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 12:01
 */

namespace App\Command\Migrate;

use App\Application\Article\Command\CreateArticle;
use App\Utils\Text;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ValidationFailedException;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * Class ArticleCommand
 *
 * @package App\Command\Migrate
 */
class ArticleCommand extends BaseCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        parent::configure();
        $this
            ->setName('app:migrate:article')
            ->setDescription('Migrates news articles from legacy database.')
            ->setHelp('This command allows you to migrate news articles from a IISHF legacy database.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): ?int
    {
        $this->io->title('Migrate news articles rom legacy database');

        $createCatList = function (string $query, string $keyColumn = 'id', string $valueColumn = 'name'): array {
            $list = [];
            foreach ($this->db->fetchAll($query) as $i) {
                $list[(int)$i[$keyColumn]] = $i[$valueColumn];
            }
            return $list;
        };
        $cat1List      = $createCatList('SELECT id, name FROM cat_cat1');
        $cat2List      = $createCatList('SELECT id, name FROM cat_cat2');
        $userList      = $createCatList('SELECT id, userid FROM sys_users', 'id', 'userid');

        $articles = $this->db->fetchAll('SELECT uid, title, cat1, cat2, subtitle, contents, edited FROM news');
        $this->io->progressStart(\count($articles));
        $results = [];
        foreach ($articles as $article) {
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

            try {
                $this->dispatchCommand($createArticle);
                $result = '';
            } catch (ValidationFailedException $e) {
                $result = implode(
                    PHP_EOL,
                    array_map(
                        function (ConstraintViolationInterface $violation) {
                            return $violation->getPropertyPath() . ': ' . $violation->getMessage();
                        },
                        iterator_to_array($e->getViolations())
                    )
                );
            } catch (\Throwable $e) {
                $result = $e->getMessage();
            }

            $results[] = [
                $createArticle->getPublishedAt()->format('Y-m-d H:i'),
                Text::shorten($createArticle->getTitle(), 64),
                empty($tags) ? '-' : implode(', ', $tags),
                $result,
            ];
            $this->io->progressAdvance();
        }
        $this->io->progressFinish();
        $this->io->table(
            ['Title'],
            $results
        );

        return 0;
    }
}
