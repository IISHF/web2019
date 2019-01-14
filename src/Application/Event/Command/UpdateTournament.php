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
use App\Domain\Model\Event\EventVenue;
use App\Domain\Model\Event\Tournament;

/**
 * Class UpdateTournament
 *
 * @package App\Application\Event\Command
 */
class UpdateTournament
{
    use UuidAware, EventProperties, TournamentProperties;

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
            EventHost::updateHost($tournament->getHost()),
            $tournament->getStartDate(),
            $tournament->getEndDate(),
            $tournament->getVenue(),
            $tournament->getTags()
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
     * @param EventVenue         $venue
     * @param string[]           $tags
     */
    private function __construct(
        string $id,
        string $name,
        int $season,
        string $ageGroup,
        EventHost $host,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        EventVenue $venue,
        array $tags
    ) {
        $this->id        = $id;
        $this->name      = $name;
        $this->season    = $season;
        $this->ageGroup  = $ageGroup;
        $this->host      = $host;
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
        $this->venue     = $venue;
        $this->tags      = $tags;
    }
}
