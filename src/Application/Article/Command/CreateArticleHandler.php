<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:25
 */

namespace App\Application\Article\Command;

use App\Domain\Model\Article\Article;
use DateTimeImmutable;

/**
 * Class CreateArticleHandler
 *
 * @package App\Application\Article\Command
 */
class CreateArticleHandler extends ArticleCommandHandler
{
    /**
     * @param CreateArticle $command
     */
    public function __invoke(CreateArticle $command): void
    {
        $article = Article::create(
            $command->getId(),
            $this->findSuitableSlug(new DateTimeImmutable('now'), $command->getTitle(), null),
            $command->getTitle(),
            $command->getSubtitle(),
            $command->getBody(),
            $command->getTags(),
            $command->getAuthor()
        );
        $this->articleRepository->save($article);
    }
}
