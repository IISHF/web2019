<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 07:55
 */

namespace App\Application\Common\Command;

use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Trait EventEmitter
 *
 * @package App\Application\Common\Command
 */
trait EventEmitter
{
    /**
     * @var MessageBusInterface
     */
    private $eventBus;

    /**
     * @param object[] $events
     */
    protected function emitEvents(iterable $events): void
    {
        foreach ($events as $event) {
            $this->emitEvent($event);
        }
    }

    /**
     * @param object $event
     */
    protected function emitEvent(object $event): void
    {
        $this->eventBus->dispatch($event);
    }
}
