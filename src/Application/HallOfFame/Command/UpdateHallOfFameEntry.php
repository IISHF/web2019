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
            $entry->getEventDate(),
            $entry->getWinnerClub(),
            $entry->getWinnerCountry(),
            $entry->getHostClub(),
            $entry->getHostCountry()
        );
    }

    /**
     * @param string                  $id
     * @param int                     $season
     * @param string                  $ageGroup
     * @param \DateTimeImmutable|null $eventDate
     * @param string                  $winnerClub
     * @param string                  $winnerCountry
     * @param string|null             $hostClub
     * @param string|null             $hostCountry
     */
    private function __construct(
        string $id,
        int $season,
        string $ageGroup,
        ?\DateTimeImmutable $eventDate,
        string $winnerClub,
        string $winnerCountry,
        ?string $hostClub,
        ?string $hostCountry
    ) {
        $this->id            = $id;
        $this->season        = $season;
        $this->ageGroup      = $ageGroup;
        $this->eventDate     = $eventDate;
        $this->winnerClub    = $winnerClub;
        $this->winnerCountry = $winnerCountry;
        $this->hostClub      = $hostClub;
        $this->hostCountry   = $hostCountry;
    }
}
