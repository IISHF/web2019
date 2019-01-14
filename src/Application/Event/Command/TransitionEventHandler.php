<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:54
 */

namespace App\Application\Event\Command;

/**
 * Class TransitionEventHandler
 *
 * @package App\Application\Event\Command
 */
class TransitionEventHandler extends WorkflowCommandHandler
{
    /**
     * @param TransitionEvent $command
     */
    public function __invoke(TransitionEvent $command): void
    {
        $event = $this->applyTransition($command);
        $this->eventRepository->save($event);
    }
}
