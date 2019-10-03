<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 10:11
 */

namespace App\Application\Event\Command\EuropeanChampionship;

use App\Application\Common\Command\IdAware;
use App\Application\Event\Command\EventProperties;
use App\Application\Event\Command\HostingProperties;
use App\Application\Event\Command\TitleEventProperties;
use App\Application\Event\EventHost;
use App\Domain\Model\Event\EuropeanChampionship;

/**
 * Class UpdateAnnouncedEuropeanChampionship
 *
 * @package App\Application\Event\Command\EuropeanChampionship
 */
class UpdateAnnouncedEuropeanChampionship
{
    use IdAware, EventProperties, TitleEventProperties, HostingProperties;

    /**
     * @param EuropeanChampionship $championship
     * @return self
     */
    public static function update(EuropeanChampionship $championship): self
    {
        if (!$championship->isAnnounced()) {
            throw new \InvalidArgumentException('The european championship is not announced.');
        }
        return new self(
            $championship->getId(),
            $championship->getName(),
            $championship->getSeason(),
            $championship->getAgeGroup(),
            $championship->getPlannedLength(),
            $championship->getDescription(),
            $championship->getTags(),
            EventHost::fromEventHostEntity($championship->getHost()),
            $championship->getStartDate(),
            $championship->getEndDate(),
            $championship->getVenue()->getId(),
            $championship->getTimeZone()
        );
    }

    /**
     * @param string             $id
     * @param string             $name
     * @param int                $season
     * @param string             $ageGroup
     * @param int                $plannedLength
     * @param string|null        $description
     * @param string[]           $tags
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
        ?string $description,
        array $tags,
        EventHost $host,
        \DateTimeImmutable $startDate,
        \DateTimeImmutable $endDate,
        string $venue,
        \DateTimeZone $timeZone
    ) {
        $this->id            = $id;
        $this->name          = $name;
        $this->season        = $season;
        $this->ageGroup      = $ageGroup;
        $this->plannedLength = $plannedLength;
        $this->description   = $description;
        $this->tags          = $tags;
        $this->host          = $host;
        $this->startDate     = $startDate;
        $this->endDate       = $endDate;
        $this->venue         = $venue;
        $this->timeZone      = $timeZone;
    }
}
