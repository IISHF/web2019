<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-10
 * Time: 12:33
 */

namespace App\Application\Article\Command;

use DateTimeImmutable;

/**
 * Class PublishArticleHandler
 *
 * @package App\Application\Article\Command
 */
class PublishArticleHandler extends WorkflowCommandHandler
{
    /**
     * @param PublishArticle $command
     */
    public function __invoke(PublishArticle $command): void
    {
        $article = $this->applyTransition($command);
        if ($command->isPublishNow()) {
            $article->setPublishedAt(new DateTimeImmutable('now'));
        } else {
            $article->setPublishedAt($command->getPublishAt());
        }
        $this->articleRepository->save($article);
    }
}
