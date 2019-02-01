<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 15:31
 */

namespace App\Application\Event\Command;

use App\Domain\Model\Event\Event;
use App\Domain\Model\Event\EventRepository;
use App\Domain\Model\Event\EventVenue;
use App\Domain\Model\Event\EventVenueRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Class EventBasedCommandHandler
 *
 * @package App\Application\Event\Command
 */
abstract class EventBasedCommandHandler implements MessageHandlerInterface
{
    /**
     * @var EventRepository
     */
    protected $eventRepository;

    /**
     * @var EventVenueRepository
     */
    private $venueRepository;

    /**
     * @param EventRepository      $eventRepository
     * @param EventVenueRepository $venueRepository
     */
    public function __construct(EventRepository $eventRepository, EventVenueRepository $venueRepository)
    {
        $this->eventRepository = $eventRepository;
        $this->venueRepository = $venueRepository;
    }

    /**
     * @param string $id
     * @return Event
     */
    protected function getEvent(string $id): Event
    {
        $event = $this->eventRepository->findById($id);
        if (!$event) {
            throw new \OutOfBoundsException('No event found for id ' . $id);
        }
        return $event;
    }

    /**
     * @param string $id
     * @return EventVenue
     */
    protected function getVenue(string $id): EventVenue
    {
        $venue = $this->venueRepository->findById($id);
        if (!$venue) {
            throw new \OutOfBoundsException('No event venue found for id ' . $id);
        }
        return $venue;
    }
}
