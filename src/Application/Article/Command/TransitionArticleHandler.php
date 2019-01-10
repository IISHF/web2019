<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-04
 * Time: 16:24
 */

namespace App\Application\Article\Command;

/**
 * Class TransitionArticleHandler
 *
 * @package App\Application\Article\Command
 */
class TransitionArticleHandler extends WorkflowCommandHandler
{
    /**
     * @param TransitionArticle $command
     */
    public function __invoke(TransitionArticle $command): void
    {
        $article = $this->applyTransition($command);
        $this->repository->save($article);
    }
}
