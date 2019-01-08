<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 08:16
 */

namespace App\Application\Common\Command;

/**
 * Class EventRecorder
 *
 * @package App\Application\Common\Command
 */
class EventRecorder implements RecordsEvents, EventRecorderInterface
{
    /**
     * @var object[]
     */
    private $events = [];

    /**
     * {@inheritdoc}
     */
    public function recordEvent(object $event): void
    {
        $this->events[] = $event;
    }

    /**
     * {@inheritdoc}
     */
    public function getEvents(): iterable
    {
        return $this->events;
    }

    /**
     * {@inheritdoc}
     */
    public function clearEvents(): void
    {
        $this->events = [];
    }
}
