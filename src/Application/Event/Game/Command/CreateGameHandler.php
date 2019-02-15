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
        $event = $this->getEvent($command->getEventId());
        $game  = Game::createWithFixture(
            $command->getId(),
            $event,
            $command->getGameType(),
            $command->getDateTime(),
            $event->getTimeZone(),
            $this->getTeam($command->getHomeTeam()),
            $this->getTeam($command->getAwayTeam()),
            $command->getRemarks()
        );
        $this->gameRepository->save($game);
    }
}
