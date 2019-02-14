<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-14
 * Time: 12:39
 */

namespace App\Application\Event\Game\Command;

/**
 * Class ChangeFixtureHandler
 *
 * @package App\Application\Event\Game\Command
 */
class ChangeFixtureHandler extends GameCommandHandler
{
    /**
     * @param ChangeFixture $command
     */
    public function __invoke(ChangeFixture $command): void
    {
        $game = $this->getGame($command->getId());
        $game->setHomeTeam($this->getTeam($command->getHomeTeam()))
             ->setAwayTeam($this->getTeam($command->getAwayTeam()));
        $this->gameRepository->save($game);
    }
}
