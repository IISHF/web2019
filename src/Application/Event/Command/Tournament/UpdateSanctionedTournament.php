<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 10:11
 */

namespace App\Application\Event\Command\Tournament;

use App\Application\Common\Command\IdAware;
use App\Application\Event\Command\EventProperties;
use App\Application\Event\Command\HostingProperties;
use App\Application\Event\Command\SanctioningProperties;
use App\Application\Event\EventHost;
use App\Domain\Model\Event\Tournament;

/**
 * Class UpdateSanctionedTournament
 *
 * @package App\Application\Event\Command\Tournament
 */
class UpdateSanctionedTournament
{
    use IdAware, EventProperties, HostingProperties, SanctioningProperties;

    /**
     * @param Tournament $tournament
     * @return self
     */
    public static function update(Tournament $tournament): self
    {
        return new self(
            $tournament->getId(),
            $tournament->getName(),
            $tournament->getSeason(),
            $tournament->getAgeGroup(),
            EventHost::fromEventHostEntity($tournament->getHost()),
            $tournament->getStartDate(),
            $tournament->getEndDate(),
            $tournament->getVenue()->getId(),
            $tournament->getTimeZone(),
            $tournament->getTags(),
            $tournament->getSanctionNumber()
        );
    }

    /**
     * @param string             $id
     * @param string             $name
     * @param int                $season
     * @param string             $ageGroup
     * @param EventHost          $host
     * @param \DateTimeImmutable $startDate
     * @param \DateTimeImmutable $endDate
     * @param string             $venue
     * @param \DateTimeZone      $timeZone
     * @param string[]           $tags
     * @param string             $sanctionNumber
     */
    private function __construct(
        string $id,
        string $name,
        int $season,
        string $ageGroup,
        EventHost $host,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        string $venue,
        \DateTimeZone $timeZone,
        array $tags,
        string $sanctionNumber
    ) {
        $this->id             = $id;
        $this->name           = $name;
        $this->season         = $season;
        $this->ageGroup       = $ageGroup;
        $this->host           = $host;
        $this->startDate      = $startDate;
        $this->endDate        = $endDate;
        $this->venue          = $venue;
        $this->timeZone       = $timeZone;
        $this->tags           = $tags;
        $this->sanctionNumber = $sanctionNumber;
    }
}
