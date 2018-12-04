<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:25
 */

namespace App\Application\Article\Command;

use App\Domain\Model\Article\Article;

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
        if ($command->isLegacyFormat()) {
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
        } else {
            $article = Article::create(
                $command->getId(),
                $this->findSuitableSlug($command->getPublishedAt(), $command->getTitle(), null),
                $command->getTitle(),
                $command->getSubtitle(),
                $command->getBody(),
                $command->getTags(),
                $command->getAuthor(),
                $command->getPublishedAt()
            );
        }

        $this->repository->save($article);
    }
}
