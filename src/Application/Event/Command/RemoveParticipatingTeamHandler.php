<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 15:59
 */

namespace App\Application\Event\Command;

/**
 * Class RemoveParticipatingTeamHandler
 *
 * @package App\Application\Event\Command
 */
class RemoveParticipatingTeamHandler extends ParticipatingTeamCommandHandler
{
    /**
     * @param RemoveParticipatingTeam $command
     */
    public function __invoke(RemoveParticipatingTeam $command): void
    {
        $team = $this->getTeam($command->getId());
        $this->teamRepository->delete($team);
    }
}
