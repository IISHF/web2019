<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 15:25
 */

namespace App\Application\Event\Team\Command;

use App\Application\Event\Command\EventBasedCommandHandler;
use App\Domain\Model\Event\EventRepository;
use App\Domain\Model\Event\Team\ParticipatingTeam;
use App\Domain\Model\Event\Team\ParticipatingTeamRepository;
use OutOfBoundsException;

/**
 * Class ParticipatingTeamCommandHandler
 *
 * @package App\Application\Event\Team\Command
 */
abstract class ParticipatingTeamCommandHandler extends EventBasedCommandHandler
{
    /**
     * @var ParticipatingTeamRepository
     */
    protected $teamRepository;

    /**
     * @param EventRepository             $eventRepository
     * @param ParticipatingTeamRepository $teamRepository
     */
    public function __construct(EventRepository $eventRepository, ParticipatingTeamRepository $teamRepository)
    {
        parent::__construct($eventRepository);
        $this->teamRepository = $teamRepository;
    }

    /**
     * @param string $id
     * @return ParticipatingTeam
     */
    protected function getTeam(string $id): ParticipatingTeam
    {
        $team = $this->teamRepository->findById($id);
        if (!$team) {
            throw new OutOfBoundsException('No participating team found for id ' . $id);
        }
        return $team;
    }
}
