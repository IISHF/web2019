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
     * @param EventRepository $eventRepository
     */
    public function __construct(EventRepository $eventRepository)
    {
        $this->eventRepository = $eventRepository;
    }

    /**
     * @param string      $id
     * @param string|null $type #Entity
     * @return Event
     */
    protected function getEvent(string $id, ?string $type = null): Event
    {
        $event = $this->eventRepository->findById($id, $type);
        if (!$event) {
            throw new \OutOfBoundsException('No event found for id ' . $id);
        }
        return $event;
    }
}
