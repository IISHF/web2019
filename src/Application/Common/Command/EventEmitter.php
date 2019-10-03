<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 07:55
 */

namespace App\Application\Common\Command;

use BadMethodCallException;

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
     * @param RecordsEvents $eventRecorder
     * @internal
     */
    public function setEventRecorder(RecordsEvents $eventRecorder): void
    {
        $this->eventRecorder = $eventRecorder;
    }

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
        if (!$this->eventRecorder) {
            throw new BadMethodCallException('No event recorder available.');
        }
        $this->eventRecorder->recordEvent($event);
    }
}
