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
        $article = new Article(
            $command->getId(),
            $command->getSlug(),
            $command->getTitle(),
            $command->getBody(),
            $command->getTags()
        );

        $this->repository->save($article);
    }
}
