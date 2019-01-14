<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 17:01
 */

namespace App\Application\Event\Command;

use App\Domain\Model\Event\TitleEvent;
use Ramsey\Uuid\Uuid;

/**
 * Class AwardTitleEventHandler
 *
 * @package App\Application\Event\Command
 */
class AwardTitleEventHandler extends WorkflowCommandHandler
{
    /**
     * @param AwardTitleEvent $command
     */
    public function __invoke(AwardTitleEvent $command): void
    {
        /** @var TitleEvent $event */
        $event = $this->applyTransition($command);
        $event->awardEventApplication((string)Uuid::uuid4(), $command->getApplication());
        $this->eventRepository->save($event);
    }
}
