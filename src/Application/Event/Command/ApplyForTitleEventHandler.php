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
        $event = $this->getEvent($command->getEventId());
        if (!$event instanceof TitleEvent) {
            throw new \InvalidArgumentException('Invalid event - ' . TitleEvent::class . ' required.');
        }
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
            $this->getVenue($command->getVenue())
        );
        $this->applicationRepository->save($application);
    }
}
