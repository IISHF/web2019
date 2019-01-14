<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-10
 * Time: 11:02
 */

namespace App\Application\Article\Command;

use App\Domain\Model\Article\Article;

/**
 * Class CreateLegacyArticleHandler
 *
 * @package App\Application\Article\Command
 */
class CreateLegacyArticleHandler extends ArticleCommandHandler
{
    /**
     * @param CreateLegacyArticle $command
     */
    public function __invoke(CreateLegacyArticle $command): void
    {
        $article = Article::createLegacy(
            $command->getId(),
            $this->findSuitableSlug($command->getPublishedAt(), $command->getTitle(), null),
            $command->getTitle(),
            $command->getSubtitle(),
            $command->getBody(),
            $command->getTags(),
            $command->getAuthor(),
            $command->getPublishedAt()
        );
        $this->articleRepository->save($article);
    }
}
