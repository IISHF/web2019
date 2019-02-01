<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 15:25
 */

namespace App\Application\Event\Command;

use App\Domain\Model\Event\EventRepository;
use App\Domain\Model\Event\EventVenueRepository;
use App\Domain\Model\Event\ParticipatingTeam;
use App\Domain\Model\Event\ParticipatingTeamRepository;

/**
 * Class ParticipatingTeamCommandHandler
 *
 * @package App\Application\Event\Command
 */
abstract class ParticipatingTeamCommandHandler extends EventBasedCommandHandler
{
    /**
     * @var ParticipatingTeamRepository
     */
    protected $teamRepository;

    /**
     * @param EventRepository             $eventRepository
     * @param EventVenueRepository        $venueRepository
     * @param ParticipatingTeamRepository $teamRepository
     */
    public function __construct(
        EventRepository $eventRepository,
        EventVenueRepository $venueRepository,
        ParticipatingTeamRepository $teamRepository
    ) {
        parent::__construct($eventRepository, $venueRepository);
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
            throw new \OutOfBoundsException('No participating team found for id ' . $id);
        }
        return $team;
    }
}
