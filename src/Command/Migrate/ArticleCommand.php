<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 12:01
 */

namespace App\Command\Migrate;

use App\Application\Article\Command\CreateArticle;
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

        $articles = $this->db->fetchAll('SELECT title, subtitle, contents, edited FROM news');
        $this->io->progressStart(\count($articles));
        $results = [];
        foreach ($articles as $article) {
            $createArticle = CreateArticle::create()
                                          ->setTitle($article['title'])
                                          ->setBody($article['contents']);

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
                $createArticle->getTitle(),
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
