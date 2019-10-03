<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 08:18
 */

namespace App\Infrastructure\Command;

use App\Application\Common\Command\EventRecorderInterface;
use Exception;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

/**
 * Class HandlesRecordedEventsMiddleware
 *
 * @package App\Infrastructure\Command
 */
class HandlesRecordedEventsMiddleware implements MiddlewareInterface
{
    /**
     * @var EventRecorderInterface
     */
    private $eventRecorder;

    /**
     * @var MessageBusInterface
     */
    private $eventBus;

    /**
     * @param EventRecorderInterface $eventRecorder
     * @param MessageBusInterface    $eventBus
     */
    public function __construct(EventRecorderInterface $eventRecorder, MessageBusInterface $eventBus)
    {
        $this->eventRecorder = $eventRecorder;
        $this->eventBus      = $eventBus;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        try {
            $envelope = $stack->next()->handle($envelope, $stack);
        } catch (Exception $exception) {
            $this->eventRecorder->clearEvents();
            throw $exception;
        }

        $recordedEvents = $this->eventRecorder->getEvents();

        $this->eventRecorder->clearEvents();

        foreach ($recordedEvents as $recordedEvent) {
            $this->eventBus->dispatch($recordedEvent);
        }
        return $envelope;
    }
}
