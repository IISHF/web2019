<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-12-04
 * Time: 16:24
 */

namespace App\Application\Article\Command;

use App\Domain\Model\Article\ArticleRepository;
use Symfony\Component\Workflow\Registry;

/**
 * Class WorkflowCommandHandler
 *
 * @package App\Application\Article\Command
 */
class WorkflowCommandHandler extends ArticleCommandHandler
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
     * @param WorkflowCommand $command
     */
    public function __invoke(WorkflowCommand $command): void
    {
        $article  = $this->getArticle($command->getId());
        $workflow = $this->workflowRegistry->get($article, 'article_publishing');
        $workflow->apply($article, $command->getTransition());
        $this->repository->save($article);
    }
}
