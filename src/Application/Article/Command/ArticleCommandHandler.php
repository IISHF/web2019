<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:25
 */

namespace App\Application\Article\Command;

use App\Domain\Common\Urlizer;
use App\Domain\Model\Article\Article;
use App\Domain\Model\Article\ArticleRepository;
use App\Utils\Text;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class ArticleCommandHandler
 *
 * @package App\Application\Article\Command
 */
abstract class ArticleCommandHandler implements MessageHandlerInterface
{
    /**
     * @var ArticleRepository
     */
    protected $articleRepository;

    /**
     * @param ArticleRepository $articleRepository
     */
    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    /**
     * @param string $id
     * @return Article
     */
    protected function getArticle(string $id): Article
    {
        $article = $this->articleRepository->findById($id);
        if (!$article) {
            throw new \OutOfBoundsException('No article found for id ' . $id);
        }
        return $article;
    }

    /**
     * @param \DateTimeImmutable $publishedDate
     * @param string             $title
     * @param string|null        $id
     * @return string
     */
    protected function findSuitableSlug(\DateTimeImmutable $publishedDate, string $title, ?string $id): string
    {
        return Urlizer::urlizeUnique(
            $publishedDate->format('Y-m-d') . '-' . Text::shorten($title, 100),
            function (string $slug) use ($id) {
                return ($tryArticle = $this->articleRepository->findBySlug($slug)) !== null
                    && $tryArticle->getId() !== $id;
            }
        );
    }
}
