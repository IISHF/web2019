<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 09:20
 */

namespace App\Application\Event\Game\Command;

use App\Domain\Model\Event\Game\Game;
use App\Domain\Model\Event\Game\GameResult;

/**
 * Class CreateGameHandler
 *
 * @package App\Application\Event\Game\Command
 */
class CreateGameHandler extends GameCommandHandler
{
    /**
     * @param CreateGame $command
     */
    public function __invoke(CreateGame $command): void
    {
        $game = new Game(
            $command->getId(),
            $this->getEvent($command->getEventId()),
            $command->getGameType(),
            $command->getDateTime(),
            $this->getTeam($command->getHomeTeam()),
            $this->getTeam($command->getAwayTeam()),
            $command->getRemarks(),
            GameResult::create($command->getHomeGoals(), $command->getAwayGoals())
        );
        $this->gameRepository->save($game);
    }
}
