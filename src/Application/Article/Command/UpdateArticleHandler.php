<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:25
 */

namespace App\Application\Article\Command;

/**
 * Class UpdateArticleHandler
 *
 * @package App\Application\Article\Command
 */
class UpdateArticleHandler extends ArticleCommandHandler
{
    /**
     * @param UpdateArticle $command
     */
    public function __invoke(UpdateArticle $command): void
    {
        $article = $command->getArticle();
        $article->setSlug($this->findSuitableSlug(new \DateTimeImmutable(), $command->getTitle(), $article->getId()))
                ->setTitle($command->getTitle())
                ->setSubtitle($command->getSubtitle())
                ->setBody($command->getBody())
                ->setTags($command->getTags())
                ->setPublishedAt($command->getPublishedAt());

        $this->repository->save($article);
    }
}
