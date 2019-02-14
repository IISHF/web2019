<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:58
 */

namespace App\Application\Event\Command\Workflow;

/**
 * Class SanctionEventHandler
 *
 * @package App\Application\Event\Command
 */
class SanctionEventHandler extends WorkflowCommandHandler
{
    /**
     * @param SanctionEvent $command
     */
    public function __invoke(SanctionEvent $command): void
    {
        $event = $this->applyTransition($command);
        $event->setSanctionNumber($command->getSanctionNumber());
        $this->eventRepository->save($event);
    }
}
