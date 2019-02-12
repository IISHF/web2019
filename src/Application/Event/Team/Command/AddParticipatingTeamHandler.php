<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 15:30
 */

namespace App\Application\Event\Team\Command;

use App\Domain\Model\Event\Team\ParticipatingTeamContact;
use Ramsey\Uuid\Uuid;

/**
 * Class AddParticipatingTeamHandler
 *
 * @package App\Application\Event\Team\Command
 */
class AddParticipatingTeamHandler extends ParticipatingTeamCommandHandler
{
    /**
     * @param AddParticipatingTeam $command
     */
    public function __invoke(AddParticipatingTeam $command): void
    {
        $event   = $this->getEvent($command->getEventId());
        $contact = $command->getContact();
        $team    = $event->createParticipant($command->getId(), $command->getName());
        $team->setContact(
            new ParticipatingTeamContact(
                (string)Uuid::uuid4(),
                $contact->getName(),
                $contact->getEmail(),
                $contact->getPhoneNumber()
            )
        );
        $this->teamRepository->save($team);
    }
}
