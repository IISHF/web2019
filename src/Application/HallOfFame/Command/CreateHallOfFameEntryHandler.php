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
        if ($command->isChampionship()) {
            $entry = HallOfFameEntry::forChampionship(
                $command->getId(),
                $command->getSeason(),
                $command->getAgeGroup(),
                $command->getEventDate(),
                $command->getWinnerClub(),
                $command->getWinnerCountry(),
                $command->getHostClub(),
                $command->getHostCountry()
            );
        } else {
            $entry = HallOfFameEntry::forCup(
                $command->getId(),
                $command->getSeason(),
                $command->getAgeGroup(),
                $command->getEventDate(),
                $command->getWinnerClub(),
                $command->getWinnerCountry(),
                $command->getHostClub(),
                $command->getHostCountry()
            );
        }
        $this->hallOfFameRepository->save($entry);
    }
}
