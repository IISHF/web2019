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
use App\Domain\Model\Event\EuropeanCup;
use App\Domain\Model\Event\EventVenue;

/**
 * Class UpdateEuropeanCup
 *
 * @package App\Application\Event\Command
 */
class UpdateEuropeanCup implements HasSanctionStatus, HasAnnouncementStatus
{
    use UuidAware, EventProperties, TitleEventProperties, EuropeanCupProperties;

    /**
     * @param EuropeanCup $cup
     * @return self
     */
    public static function update(EuropeanCup $cup): self
    {
        $announced = $cup->isAnnounced();
        $host      = null;
        $startDate = null;
        $endDate   = null;
        $venue     = null;
        if ($announced) {
            $host      = EventHost::fromEventHostEntity($cup->getHost());
            $startDate = $cup->getStartDate();
            $endDate   = $cup->getEndDate();
            $venue     = $cup->getVenue();
        }

        return new self(
            $cup->getId(),
            $cup->getName(),
            $cup->getSeason(),
            $cup->getAgeGroup(),
            $cup->getPlannedLength(),
            $cup->getPlannedTeams(),
            $cup->getDescription(),
            $cup->getTags(),
            $cup->getSanctionNumber(),
            $announced,
            $host,
            $startDate,
            $endDate,
            $venue
        );
    }

    /**
     * @param string                  $id
     * @param string                  $name
     * @param int                     $season
     * @param string                  $ageGroup
     * @param int                     $plannedLength
     * @param int                     $plannedTeams
     * @param string|null             $description
     * @param string[]                $tags
     * @param string|null             $sanctionNumber
     * @param bool                    $announced
     * @param EventHost|null          $host
     * @param \DateTimeImmutable|null $startDate
     * @param \DateTimeImmutable|null $endDate
     * @param EventVenue|null         $venue
     */
    private function __construct(
        string $id,
        string $name,
        int $season,
        string $ageGroup,
        int $plannedLength,
        int $plannedTeams,
        ?string $description,
        array $tags,
        ?string $sanctionNumber,
        bool $announced,
        ?EventHost $host,
        ?\DateTimeImmutable $startDate,
        ?\DateTimeImmutable $endDate,
        ?EventVenue $venue
    ) {
        $this->id             = $id;
        $this->name           = $name;
        $this->season         = $season;
        $this->ageGroup       = $ageGroup;
        $this->plannedLength  = $plannedLength;
        $this->plannedTeams   = $plannedTeams;
        $this->description    = $description;
        $this->tags           = $tags;
        $this->sanctionNumber = $sanctionNumber;
        $this->sanctioned     = $sanctionNumber !== null;
        $this->announced      = $announced;
        $this->host           = $host;
        $this->startDate      = $startDate;
        $this->endDate        = $endDate;
        $this->venue          = $venue;
    }
}
