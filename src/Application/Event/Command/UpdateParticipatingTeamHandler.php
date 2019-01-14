<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 16:09
 */

namespace App\Application\Event\Command;

use App\Domain\Model\Event\ParticipatingTeamContact;
use Ramsey\Uuid\Uuid;

/**
 * Class UpdateParticipatingTeamHandler
 *
 * @package App\Application\Event\Command
 */
class UpdateParticipatingTeamHandler extends ParticipatingTeamCommandHandler
{
    /**
     * @param UpdateParticipatingTeam $command
     */
    public function __invoke(UpdateParticipatingTeam $command): void
    {
        $team = $this->getTeam($command->getId());
        $team->setName($command->getName());

        $contact     = $command->getContact();
        $teamContact = $team->getContact();
        if ($teamContact) {
            $teamContact->setName($contact->getName())
                        ->setEmail($contact->getEmail())
                        ->setPhoneNumber($contact->getPhoneNumber());
        } else {
            $team->setContact(
                new ParticipatingTeamContact(
                    (string)Uuid::uuid4(),
                    $contact->getName(),
                    $contact->getEmail(),
                    $contact->getPhoneNumber()
                )
            );
        }
        $this->teamRepository->save($team);
    }
}
