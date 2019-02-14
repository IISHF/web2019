<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-14
 * Time: 12:38
 */

namespace App\Application\Event\Game\Command;

use App\Domain\Model\Event\Game\GameResult;

/**
 * Class RecordResultHandler
 *
 * @package App\Application\Event\Game\Command
 */
class RecordResultHandler extends GameCommandHandler
{
    /**
     * @param RecordResult $command
     */
    public function __invoke(RecordResult $command): void
    {
        $game = $this->getGame($command->getId());
        $game->setResult(GameResult::create($command->getHomeGoals(), $command->getAwayGoals()));
        $this->gameRepository->save($game);
    }
}
