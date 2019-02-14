<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 17:01
 */

namespace App\Application\Event\Command\Workflow;

use App\Domain\Model\Event\TitleEvent;
use Ramsey\Uuid\Uuid;

/**
 * Class AnnounceTitleEventHandler\Workflow
 *
 * @package App\Application\Event\Command
 */
class AnnounceTitleEventHandler extends WorkflowCommandHandler
{
    /**
     * @param AnnounceTitleEvent $command
     */
    public function __invoke(AnnounceTitleEvent $command): void
    {
        /** @var TitleEvent $event */
        $event = $this->applyTransition($command);
        $event->announceTitleEvent((string)Uuid::uuid4(), $command->getApplication());
        $this->eventRepository->save($event);
    }
}
