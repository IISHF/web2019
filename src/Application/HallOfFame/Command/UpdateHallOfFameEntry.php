<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-05
 * Time: 09:09
 */

namespace App\Application\HallOfFame\Command;

use App\Application\Common\Command\UuidAware;
use App\Domain\Model\HallOfFame\HallOfFameEntry;

/**
 * Class UpdateHallOfFameEntry
 *
 * @package App\Application\HallOfFame\Command
 */
class UpdateHallOfFameEntry
{
    use UuidAware, HallOfFameEntryProperties;

    /**
     * @param HallOfFameEntry $entry
     * @return self
     */
    public static function update(HallOfFameEntry $entry): self
    {
        return new self(
            $entry->getId(),
            $entry->getSeason(),
            $entry->getAgeGroup(),
            $entry->getEvent(),
            $entry->getEventDate(),
            $entry->isChampionship(),
            $entry->getWinnerClub(),
            $entry->getWinnerCountry(),
            $entry->getHostClub(),
            $entry->getHostCountry()
        );
    }

    /**
     * @param string      $id
     * @param int         $season
     * @param string      $ageGroup
     * @param string      $event
     * @param string|null $eventDate
     * @param bool        $championship
     * @param string      $winnerClub
     * @param string      $winnerCountry
     * @param string|null $hostClub
     * @param string|null $hostCountry
     */
    private function __construct(
        string $id,
        int $season,
        string $ageGroup,
        string $event,
        ?string $eventDate,
        bool $championship,
        string $winnerClub,
        string $winnerCountry,
        ?string $hostClub,
        ?string $hostCountry
    ) {
        $this->id            = $id;
        $this->season        = $season;
        $this->ageGroup      = $ageGroup;
        $this->event         = $event;
        $this->eventDate     = $eventDate;
        $this->championship  = $championship;
        $this->winnerClub    = $winnerClub;
        $this->winnerCountry = $winnerCountry;
        $this->hostClub      = $hostClub;
        $this->hostCountry   = $hostCountry;
    }
}
