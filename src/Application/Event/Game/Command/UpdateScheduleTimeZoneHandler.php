<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-11
 * Time: 07:27
 */

namespace App\Application\Event\Game\Command;

/**
 * Class UpdateScheduleTimeZoneHandler
 *
 * @package App\Application\Event\Game\Command
 */
class UpdateScheduleTimeZoneHandler extends GameCommandHandler
{
    /**
     * @param UpdateScheduleTimeZone $command
     */
    public function __invoke(UpdateScheduleTimeZone $command): void
    {
        $event = $this->getEvent($command->getEventId());
        $games = $this->gameRepository->findForEvent($event);
        foreach ($games as $game) {
            $game->updateTimeZone($command->getTimeZone());
        }
        $this->gameRepository->save(...$games);
    }
}
