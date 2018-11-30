<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 14:24
 */

namespace App\Application\Article\Command;

/**
 * Class SubmitArticleHandler
 *
 * @package App\Application\Article\Command
 */
class SubmitArticleHandler extends ArticleWorkflowCommandHandler
{
    /**
     * @param SubmitArticle $command
     */
    public function __invoke(SubmitArticle $command): void
    {
        $article = $this->applyWorkflowTransition($command->getArticle(), 'submit');
        $this->repository->save($article);
    }
}
