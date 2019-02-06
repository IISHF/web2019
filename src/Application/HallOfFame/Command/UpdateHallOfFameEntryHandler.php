<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-05
 * Time: 09:16
 */

namespace App\Application\HallOfFame\Command;

/**
 * Class UpdateHallOfFameEntryHandler
 *
 * @package App\Application\HallOfFame\Command
 */
class UpdateHallOfFameEntryHandler extends HallOfFameEntryCommandHandler
{
    /**
     * @param UpdateHallOfFameEntry $command
     */
    public function __invoke(UpdateHallOfFameEntry $command): void
    {
        $entry = $this->getEntry($command->getId());
        $entry->setSeason($command->getSeason())
              ->setAgeGroup($command->getAgeGroup())
              ->setEvent($command->getEvent())
              ->setEventDate($command->getEventDate())
              ->setChampionship($command->isChampionship())
              ->setWinnerClub($command->getWinnerClub())
              ->setWinnerCountry($command->getWinnerCountry())
              ->setHostClub($command->getHostClub())
              ->setHostCountry($command->getHostCountry());
        $this->hallOfFameRepository->save($entry);
    }
}
