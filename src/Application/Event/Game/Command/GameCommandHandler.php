<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 09:11
 */

namespace App\Application\Event\Game\Command;

use App\Application\Common\Command\CommandHandler;
use App\Domain\Model\Event\Event;
use App\Domain\Model\Event\EventRepository;
use App\Domain\Model\Event\Game\Game;
use App\Domain\Model\Event\Game\GameRepository;
use App\Domain\Model\Event\Team\ParticipatingTeam;
use App\Domain\Model\Event\Team\ParticipatingTeamRepository;

/**
 * Class GameCommandHandler
 *
 * @package App\Application\Event\Game\Command
 */
abstract class GameCommandHandler implements CommandHandler
{
    /**
     * @var GameRepository
     */
    protected $gameRepository;

    /**
     * @var EventRepository
     */
    protected $eventRepository;

    /**
     * @var ParticipatingTeamRepository
     */
    protected $teamRepository;

    /**
     * @param GameRepository              $gameRepository
     * @param EventRepository             $eventRepository
     * @param ParticipatingTeamRepository $teamRepository
     */
    public function __construct(
        GameRepository $gameRepository,
        EventRepository $eventRepository,
        ParticipatingTeamRepository $teamRepository
    ) {
        $this->gameRepository  = $gameRepository;
        $this->eventRepository = $eventRepository;
        $this->teamRepository  = $teamRepository;
    }

    /**
     * @param string $id
     * @return Game
     */
    protected function getGame(string $id): Game
    {
        $game = $this->gameRepository->findById($id);
        if (!$game) {
            throw new \OutOfBoundsException('No game found for id ' . $id);
        }
        return $game;
    }

    /**
     * @param string $id
     * @return Event
     */
    protected function getEvent(string $id): Event
    {
        $event = $this->eventRepository->findById($id);
        if (!$event) {
            throw new \OutOfBoundsException('No event found for id ' . $id);
        }
        return $event;
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
