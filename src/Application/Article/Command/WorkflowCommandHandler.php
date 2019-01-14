<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-10
 * Time: 12:21
 */

namespace App\Application\Article\Command;

use App\Domain\Model\Article\Article;
use App\Domain\Model\Article\ArticleRepository;
use Symfony\Component\Workflow\Registry;

/**
 * Class WorkflowCommandHandler
 *
 * @package App\Application\Article\Command
 */
abstract class WorkflowCommandHandler extends ArticleCommandHandler
{
    /**
     * @var Registry
     */
    private $workflowRegistry;

    /**
     * @param ArticleRepository $articleRepository
     * @param Registry          $workflowRegistry
     */
    public function __construct(ArticleRepository $articleRepository, Registry $workflowRegistry)
    {
        parent::__construct($articleRepository);
        $this->workflowRegistry = $workflowRegistry;
    }

    /**
     * @param ArticleWorkflowCommand $command
     * @return Article
     */
    protected function applyTransition(ArticleWorkflowCommand $command): Article
    {
        $article  = $this->getArticle($command->getId());
        $workflow = $this->workflowRegistry->get($article, 'article_publishing');
        $workflow->apply($article, $command->getTransition());
        return $article;
    }
}
