<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 07:55
 */

namespace App\Application\Common\Command;

/**
 * Trait EventEmitter
 *
 * @package App\Application\Common\Command
 */
trait EventEmitter
{
    /**
     * @var RecordsEvents
     */
    private $eventRecorder;

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
        $this->eventRecorder->recordEvent($event);
    }
}
