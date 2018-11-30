<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 14:24
 */

namespace App\Application\Article\Command;

/**
 * Class RejectArticleHandler
 *
 * @package App\Application\Article\Command
 */
class RejectArticleHandler extends ArticleWorkflowCommandHandler
{
    /**
     * @param RejectArticle $command
     */
    public function __invoke(RejectArticle $command): void
    {
        $article = $this->applyWorkflowTransition($command->getArticle(), 'reject');
        $this->repository->save($article);
    }
}
