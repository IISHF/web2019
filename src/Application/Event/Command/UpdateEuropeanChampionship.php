<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 10:11
 */

namespace App\Application\Event\Command;

use App\Application\Common\Command\UuidAware;
use App\Application\Event\EventHost;
use App\Domain\Model\Event\EuropeanChampionship;

/**
 * Class UpdateEuropeanChampionship
 *
 * @package App\Application\Event\Command
 */
class UpdateEuropeanChampionship implements HasSanctionStatus, HasAnnouncementStatus
{
    use UuidAware, EventProperties, TitleEventProperties;

    /**
     * @param EuropeanChampionship $championship
     * @return self
     */
    public static function update(EuropeanChampionship $championship): self
    {
        $announced = $championship->isAnnounced();
        $host      = null;
        $startDate = null;
        $endDate   = null;
        $venue     = null;
        $timeZone  = null;
        if ($announced) {
            $host      = EventHost::fromEventHostEntity($championship->getHost());
            $startDate = $championship->getStartDate();
            $endDate   = $championship->getEndDate();
            $venue     = $championship->getVenue()->getId();
            $timeZone  = $championship->getTimeZone();
        }

        return new self(
            $championship->getId(),
            $championship->getName(),
            $championship->getSeason(),
            $championship->getAgeGroup(),
            $championship->getPlannedLength(),
            $championship->getDescription(),
            $championship->getTags(),
            $championship->getSanctionNumber(),
            $announced,
            $host,
            $startDate,
            $endDate,
            $venue,
            $timeZone
        );
    }

    /**
     * @param string                  $id
     * @param string                  $name
     * @param int                     $season
     * @param string                  $ageGroup
     * @param int                     $plannedLength
     * @param string|null             $description
     * @param string[]                $tags
     * @param string|null             $sanctionNumber
     * @param bool                    $announced
     * @param EventHost|null          $host
     * @param \DateTimeImmutable|null $startDate
     * @param \DateTimeImmutable|null $endDate
     * @param string|null             $venue
     * @param \DateTimeZone|null      $timeZone
     */
    private function __construct(
        string $id,
        string $name,
        int $season,
        string $ageGroup,
        int $plannedLength,
        ?string $description,
        array $tags,
        ?string $sanctionNumber,
        bool $announced,
        ?EventHost $host,
        ?\DateTimeImmutable $startDate,
        ?\DateTimeImmutable $endDate,
        ?string $venue,
        ?\DateTimeZone $timeZone
    ) {
        $this->id             = $id;
        $this->name           = $name;
        $this->season         = $season;
        $this->ageGroup       = $ageGroup;
        $this->plannedLength  = $plannedLength;
        $this->description    = $description;
        $this->tags           = $tags;
        $this->sanctionNumber = $sanctionNumber;
        $this->sanctioned     = $sanctionNumber !== null;
        $this->announced      = $announced;
        $this->host           = $host;
        $this->startDate      = $startDate;
        $this->endDate        = $endDate;
        $this->venue          = $venue;
        $this->timeZone       = $timeZone;
    }
}
