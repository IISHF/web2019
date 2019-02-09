<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 09:21
 */

namespace App\Application\Event\Game\Command;

/**
 * Class DeleteGameHandler
 *
 * @package App\Application\Event\Game\Command
 */
class DeleteGameHandler extends GameCommandHandler
{
    /**
     * @param DeleteGame $command
     */
    public function __invoke(DeleteGame $command): void
    {
        $game = $this->getGame($command->getId());
        $this->gameRepository->delete($game);
    }
}
