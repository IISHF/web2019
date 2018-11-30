<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 11:26
 */

namespace App\Application\Article\Command;

/**
 * Class DeleteArticleHandler
 *
 * @package App\Application\Article\Command
 */
class DeleteArticleHandler extends ArticleCommandHandler
{
    /**
     * @param DeleteArticle $command
     */
    public function __invoke(DeleteArticle $command): void
    {
        $this->repository->delete($command->getArticle());
    }
}
