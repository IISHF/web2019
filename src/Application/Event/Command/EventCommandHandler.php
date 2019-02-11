<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 15:16
 */

namespace App\Application\Event\Command;

use App\Application\Common\Command\CommandDispatcher;
use App\Domain\Common\Urlizer;
use App\Domain\Model\Event\EventRepository;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class EventCommandHandler
 *
 * @package App\Application\Event\Command
 */
abstract class EventCommandHandler extends EventBasedCommandHandler
{
    use CommandDispatcher;

    /**
     * @param EventRepository     $eventRepository
     * @param MessageBusInterface $commandBus
     */
    public function __construct(EventRepository $eventRepository, MessageBusInterface $commandBus)
    {
        parent::__construct($eventRepository);
        $this->commandBus = $commandBus;
    }

    /**
     * @param int         $season
     * @param string      $name
     * @param string|null $id
     * @return string
     */
    protected function findSuitableSlug(int $season, string $name, ?string $id): string
    {
        return Urlizer::urlizeUnique(
            $name,
            function (string $slug) use ($id, $season) {
                return ($tryEvent = $this->eventRepository->findBySlug($season, $slug)) !== null
                    && $tryEvent->getId() !== $id;
            }
        );
    }
}
