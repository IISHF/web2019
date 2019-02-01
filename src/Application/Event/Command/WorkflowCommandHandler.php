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
use App\Domain\Model\Event\EventVenueRepository;
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
     * @param EventRepository      $eventRepository
     * @param EventVenueRepository $venueRepository
     * @param Registry             $workflowRegistry
     */
    public function __construct(
        EventRepository $eventRepository,
        EventVenueRepository $venueRepository,
        Registry $workflowRegistry
    ) {
        parent::__construct($eventRepository, $venueRepository);
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
