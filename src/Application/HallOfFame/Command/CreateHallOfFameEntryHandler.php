<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-05
 * Time: 09:12
 */

namespace App\Application\HallOfFame\Command;

use App\Domain\Model\HallOfFame\HallOfFameEntry;

/**
 * Class CreateHallOfFameEntryHandler
 *
 * @package App\Application\HallOfFame\Command
 */
class CreateHallOfFameEntryHandler extends HallOfFameEntryCommandHandler
{
    /**
     * @param CreateHallOfFameEntry $command
     */
    public function __invoke(CreateHallOfFameEntry $command): void
    {
        $entry = new HallOfFameEntry(
            $command->getId(),
            $command->getSeason(),
            $command->getAgeGroup(),
            $command->getEvent(),
            $command->getEventDate(),
            $command->isChampionship(),
            $command->getWinnerClub(),
            $command->getWinnerCountry(),
            $command->getHostClub(),
            $command->getHostCountry()
        );
        $entry->setSecondPlaceClub($command->getSecondPlaceClub())
              ->setSecondPlaceCountry($command->getSecondPlaceCountry())
              ->setThirdPlaceClub($command->getThirdPlaceClub())
              ->setThirdPlaceCountry($command->getThirdPlaceCountry());
        $this->hallOfFameRepository->save($entry);
    }
}
