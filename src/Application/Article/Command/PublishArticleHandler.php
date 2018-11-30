<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 14:24
 */

namespace App\Application\Article\Command;

/**
 * Class PublishArticleHandler
 *
 * @package App\Application\Article\Command
 */
class PublishArticleHandler extends ArticleWorkflowCommandHandler
{
    /**
     * @param PublishArticle $command
     */
    public function __invoke(PublishArticle $command): void
    {
        $article = $this->applyWorkflowTransition($command->getArticle(), 'publish');
        $this->repository->save($article);
    }
}
