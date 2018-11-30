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
        $article->setSlug($command->getSlug())
                ->setTitle($command->getTitle())
                ->setBody($command->getBody())
                ->setTags($command->getTags());

        $this->repository->save($article);
    }
}
