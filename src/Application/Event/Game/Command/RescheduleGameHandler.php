<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-14
 * Time: 12:38
 */

namespace App\Application\Event\Game\Command;

/**
 * Class RescheduleGameHandler
 *
 * @package App\Application\Event\Game\Command
 */
class RescheduleGameHandler extends GameCommandHandler
{
    /**
     * @param RescheduleGame $command
     */
    public function __invoke(RescheduleGame $command): void
    {
        $game = $this->getGame($command->getId());
        $game->reschedule($command->getDateTime());
        $this->gameRepository->save($game);
    }
}
