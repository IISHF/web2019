<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 10:11
 */

namespace App\Application\Event\Command\EuropeanCup;

use App\Application\Common\Command\IdAware;
use App\Application\Event\Command\EventProperties;
use App\Application\Event\Command\HostingProperties;
use App\Application\Event\Command\SanctioningProperties;
use App\Application\Event\Command\TitleEventProperties;
use App\Application\Event\EventHost;
use App\Domain\Model\Event\EuropeanCup;

/**
 * Class UpdateSanctionedEuropeanCup
 *
 * @package App\Application\Event\Command\EuropeanCup
 */
class UpdateSanctionedEuropeanCup
{
    use IdAware, EventProperties, TitleEventProperties, EuropeanCupProperties, HostingProperties, SanctioningProperties;

    /**
     * @param EuropeanCup $cup
     * @return self
     */
    public static function update(EuropeanCup $cup): self
    {
        if (!$cup->isSanctioned()) {
            throw new \InvalidArgumentException('The european cup is not sanctioned.');
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
            EventHost::fromEventHostEntity($cup->getHost()),
            $cup->getStartDate(),
            $cup->getEndDate(),
            $cup->getVenue()->getId(),
            $cup->getTimeZone()
        );
    }

    /**
     * @param string             $id
     * @param string             $name
     * @param int                $season
     * @param string             $ageGroup
     * @param int                $plannedLength
     * @param int                $plannedTeams
     * @param string|null        $description
     * @param string[]           $tags
     * @param string             $sanctionNumber
     * @param EventHost          $host
     * @param \DateTimeImmutable $startDate
     * @param \DateTimeImmutable $endDate
     * @param string             $venue
     * @param \DateTimeZone      $timeZone
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
        string $sanctionNumber,
        EventHost $host,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        string $venue,
        \DateTimeZone $timeZone
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
        $this->host           = $host;
        $this->startDate      = $startDate;
        $this->endDate        = $endDate;
        $this->venue          = $venue;
        $this->timeZone       = $timeZone;
    }
}
