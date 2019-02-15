<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 09:20
 */

namespace App\Application\Event\Game\Command;

use App\Domain\Model\Event\Game\Game;

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
        $event    = $this->getEvent($command->getEventId());
        $homeTeam = $command->getHomeTeamIsProvisional()
            ? $command->getHomeTeamProvisional()
            : $this->getTeam($command->getHomeTeam());
        $awayTeam = $command->getAwayTeamIsProvisional()
            ? $command->getAwayTeamProvisional()
            : $this->getTeam($command->getAwayTeam());

        $game = Game::create(
            $command->getId(),
            $event,
            $command->getGameType(),
            $command->getDateTime(),
            $event->getTimeZone(),
            $homeTeam,
            $awayTeam,
            $command->getRemarks()
        );
        $this->gameRepository->save($game);
    }
}
