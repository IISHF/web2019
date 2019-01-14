<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 15:58
 */

namespace App\Application\Event\Command;

/**
 * Class DeleteEventHandler
 *
 * @package App\Application\Event\Command
 */
class DeleteEventHandler extends EventCommandHandler
{
    /**
     * @param DeleteEvent $command
     */
    public function __invoke(DeleteEvent $command): void
    {
        $event = $this->getEvent($command->getId());
        $this->eventRepository->delete($event);
    }
}
