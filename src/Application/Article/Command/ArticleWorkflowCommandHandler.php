<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 14:32
 */

namespace App\Application\Article\Command;

use App\Domain\Model\Article\Article;
use App\Domain\Model\Article\ArticleRepository;
use Symfony\Component\Workflow\Registry;
use Symfony\Component\Workflow\Workflow;

/**
 * Class ArticleWorkflowCommandHandler
 *
 * @package App\Application\Article\Command
 */
abstract class ArticleWorkflowCommandHandler extends ArticleCommandHandler
{
    /**
     * @var Registry
     */
    private $workflowRegistry;

    /**
     * @param ArticleRepository $repository
     * @param Registry          $workflowRegistry
     */
    public function __construct(ArticleRepository $repository, Registry $workflowRegistry)
    {
        parent::__construct($repository);
        $this->workflowRegistry = $workflowRegistry;
    }

    /**
     * @param Article $article
     * @return Workflow
     */
    protected function getWorkflow(Article $article): Workflow
    {
        return $this->workflowRegistry->get($article, 'article_publishing');
    }

    /**
     * @param Article $article
     * @param string  $transitionName
     * @return Article
     */
    protected function applyWorkflowTransition(Article $article, string $transitionName): Article
    {
        $workflow = $this->getWorkflow($article);
        $workflow->apply($article, $transitionName);
        return $article;
    }
}
