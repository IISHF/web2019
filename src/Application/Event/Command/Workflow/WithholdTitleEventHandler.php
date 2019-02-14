<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-21
 * Time: 07:46
 */

namespace App\Application\Event\Command\Workflow;

use App\Domain\Model\Event\TitleEvent;

/**
 * Class WithholdTitleEventHandler
 *
 * @package App\Application\Event\Command\Workflow
 */
class WithholdTitleEventHandler extends WorkflowCommandHandler
{
    /**
     * @param WithholdTitleEvent $command
     */
    public function __invoke(WithholdTitleEvent $command): void
    {
        /** @var TitleEvent $event */
        $event = $this->applyTransition($command);
        if ($event->hasHost()) {
            $this->eventRepository->deleteHost($event->getHost());
        }
        $event->withholdTitleEVent();
        $this->eventRepository->save($event);
    }
}
