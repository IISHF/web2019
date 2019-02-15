<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 09:21
 */

namespace App\Application\Event\Game\Command;

use App\Domain\Model\Event\Game\GameResult;

/**
 * Class UpdateGameHandler
 *
 * @package App\Application\Event\Game\Command
 */
class UpdateGameHandler extends GameCommandHandler
{
    /**
     * @param UpdateGame $command
     */
    public function __invoke(UpdateGame $command): void
    {
        $game     = $this->getGame($command->getId());
        $homeTeam = $command->getHomeTeamIsProvisional()
            ? $command->getHomeTeamProvisional()
            : $this->getTeam($command->getHomeTeam());
        $awayTeam = $command->getAwayTeamIsProvisional()
            ? $command->getAwayTeamProvisional()
            : $this->getTeam($command->getAwayTeam());
        $game->setGameType($command->getGameType())
             ->reschedule($command->getDateTime())
             ->setHomeTeam($homeTeam)
             ->setAwayTeam($awayTeam)
             ->setRemarks($command->getRemarks())
             ->setResult(GameResult::create($command->getHomeGoals(), $command->getAwayGoals()));
        $this->gameRepository->save($game);
    }
}
