<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:38
 */

namespace App\Application\Event\Command;

use App\Domain\Model\Event\Event;
use App\Domain\Model\Event\EventRepository;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Workflow\Registry;

/**
 * Class WorkflowCommandHandler
 *
 * @package App\Application\Event\Command
 */
abstract class WorkflowCommandHandler extends EventCommandHandler
{
    /**
     * @var Registry
     */
    private $workflowRegistry;

    /**
     * @param EventRepository     $eventRepository
     * @param Registry            $workflowRegistry
     * @param MessageBusInterface $commandBus
     */
    public function __construct(
        EventRepository $eventRepository,
        Registry $workflowRegistry,
        MessageBusInterface $commandBus
    ) {
        parent::__construct($eventRepository, $commandBus);
        $this->workflowRegistry = $workflowRegistry;
    }

    /**
     * @param EventWorkflowCommand $command
     * @return Event
     */
    protected function applyTransition(EventWorkflowCommand $command): Event
    {
        $event    = $this->getEvent($command->getId());
        $workflow = $this->workflowRegistry->get($event);
        $workflow->apply($event, $command->getTransition());
        return $event;
    }
}
