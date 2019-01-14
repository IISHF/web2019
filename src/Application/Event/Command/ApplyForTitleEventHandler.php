<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 15:43
 */

namespace App\Application\Event\Command;

use App\Domain\Model\Common\ContactPerson;
use App\Domain\Model\Event\TitleEvent;

/**
 * Class ApplyForTitleEventHandler
 *
 * @package App\Application\Event\Command
 */
class ApplyForTitleEventHandler extends TitleEventApplicationCommandHandler
{
    /**
     * @param ApplyForTitleEvent $command
     */
    public function __invoke(ApplyForTitleEvent $command): void
    {
        /** @var TitleEvent $event */
        $event       = $this->getEvent($command->getEventId(), TitleEvent::class);
        $contact     = $command->getContact();
        $application = $event->applyForEvent(
            $command->getId(),
            $command->getApplicantClub(),
            new ContactPerson(
                $contact->getName(),
                $contact->getEmail(),
                $contact->getPhoneNumber()
            ),
            $command->getProposedStartDate(),
            $command->getProposedEndDate(),
            $command->getVenue()
        );
        $this->applicationRepository->save($application);
    }
}
