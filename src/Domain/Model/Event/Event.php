<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-11
 * Time: 12:54
 */

namespace App\Domain\Model\Event;

use App\Domain\Common\AgeGroup;
use App\Domain\Common\DateTime;
use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\HasId;
use App\Domain\Model\Common\UpdateTracking;
use App\Domain\Model\Event\Team\ParticipatingTeam;
use App\Domain\Model\Event\Venue\EventVenue;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class Event
 *
 * @package App\Domain\Model\Event
 *
 * @ORM\Entity(repositoryClass="EventRepository")
 * @ORM\Table(
 *      name="events",
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="uniq_event_name", columns={"season", "name"}),
 *          @ORM\UniqueConstraint(name="uniq_event_slug", columns={"season", "slug"})
 *      },
 *      indexes={
 *          @ORM\Index(name="idx_event_season", columns={"season", "level"})
 *      }
 * )
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="level", type="string", length=2)
 * @ORM\DiscriminatorMap({
 *      "A1" = "EuropeanChampionship",
 *      "A2" = "EuropeanCup",
 *      "B0" = "Tournament"
 * })
 */
abstract class Event
{
    use HasId, CreateTracking, UpdateTracking;

    public const STATE_PLANNED    = 'planned';
    public const STATE_SANCTIONED = 'sanctioned';

    /**
     * @ORM\Column(name="name", type="string", length=64)
     *
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(name="slug", type="string", length=128)
     *
     * @var string
     */
    private $slug;

    /**
     * @ORM\Column(name="season", type="smallint", options={"unsigned": true})
     *
     * @var int
     */
    private $season;

    /**
     * @ORM\Column(name="age_group", type="string", length=16)
     *
     * @var string
     */
    private $ageGroup;

    /**
     * @ORM\Column(name="current_state", type="string", length=16)
     *
     * @var string
     */
    private $currentState;

    /**
     * @ORM\OneToOne(targetEntity="EventHost")
     * @ORM\JoinColumn(name="host_id", referencedColumnName="id", nullable=true)
     *
     * @var EventHost|null
     */
    private $host;

    /**
     * @ORM\Column(name="sanction_number", type="string", length=16, nullable=true)
     *
     * @var string|null
     */
    private $sanctionNumber;

    /**
     * @ORM\Column(name="start_date", type="datetime_immutable", nullable=true)
     *
     * @var \DateTimeImmutable|null
     */
    private $startDate;

    /**
     * @ORM\Column(name="start_date_utc", type="datetime_immutable", nullable=true)
     *
     * @var \DateTimeImmutable
     */
    private $startDateUtc;

    /**
     * @ORM\Column(name="end_date", type="datetime_immutable", nullable=true)
     *
     * @var \DateTimeImmutable|null
     */
    private $endDate;

    /**
     * @ORM\Column(name="end_date_utc", type="datetime_immutable", nullable=true)
     *
     * @var \DateTimeImmutable
     */
    private $endDateUtc;

    /**
     * @ORM\Column(name="time_zone", type="string", length=32, nullable=true)
     *
     * @var string|null
     */
    private $timeZone;

    /**
     * @var \DateTimeZone|null
     */
    private $timeZoneInstance;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Model\Event\Venue\EventVenue")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id", nullable=true)
     *
     * @var EventVenue|null
     */
    private $venue;

    /**
     * @ORM\Column(name="tags", type="json")
     *
     * @var string[]
     */
    private $tags;

    /**
     * @param string $id
     * @param string $name
     * @param string $slug
     * @param int    $season
     * @param string $ageGroup
     * @param array  $tags
     */
    protected function __construct(
        string $id,
        string $name,
        string $slug,
        int $season,
        string $ageGroup,
        array $tags
    ) {
        $this->setId($id)
             ->setName($name)
             ->setSlug($slug)
             ->setSeason($season)
             ->setAgeGroup($ageGroup)
             ->setTags($tags)
             ->initCreateTracking()
             ->initUpdateTracking();
        $this->currentState = self::STATE_PLANNED;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): self
    {
        Assert::lengthBetween($name, 1, 64);
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     * @return $this
     */
    public function setSlug(string $slug): self
    {
        Assert::regex($slug, '/^[0-9a-z-]+$/');
        Assert::lengthBetween($slug, 1, 128);
        $this->slug = $slug;
        return $this;
    }

    /**
     * @return int
     */
    public function getSeason(): int
    {
        return $this->season;
    }

    /**
     * @param int $season
     * @return $this
     */
    public function setSeason(int $season): self
    {
        Assert::range($season, 2000, 9999);
        $this->season = $season;
        return $this;
    }

    /**
     * @return string
     */
    public function getAgeGroup(): string
    {
        return $this->ageGroup;
    }

    /**
     * @param string $ageGroup
     * @return $this
     */
    public function setAgeGroup(string $ageGroup): self
    {
        AgeGroup::assertValidAgeGroup($ageGroup);
        $this->ageGroup = $ageGroup;
        return $this;
    }

    /**
     * @return string
     */
    public function getCurrentState(): string
    {
        return $this->currentState;
    }

    /**
     * @param string $currentState
     * @return $this
     */
    public function setCurrentState(string $currentState): self
    {
        Assert::lengthBetween($currentState, 1, 16);
        $this->currentState = $currentState;
        if (!$this->isSanctioned()) {
            $this->sanctionNumber = null;
        }
        return $this;
    }

    /**
     * @return bool
     */
    public function isPlanned(): bool
    {
        return $this->currentState === self::STATE_PLANNED;
    }

    /**
     * @return bool
     */
    public function isSanctioned(): bool
    {
        return $this->currentState === self::STATE_SANCTIONED && $this->sanctionNumber !== null;
    }

    /**
     * @return bool
     */
    public function hasHost(): bool
    {
        return $this->host !== null;
    }

    /**
     * @return EventHost
     */
    public function getHost(): EventHost
    {
        if (!$this->host) {
            throw new \BadMethodCallException('Cannot get host. No host set.');
        }
        return $this->host;
    }

    /**
     * @param EventHost $host
     * @return $this
     */
    public function setHost(EventHost $host): self
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return $this
     */
    public function clearHost(): self
    {
        $this->host = null;
        return $this;
    }

    /**
     * @return string
     */
    public function getSanctionNumber(): string
    {
        if (!$this->isSanctioned()) {
            throw new \BadMethodCallException('Cannot get sanction number. Event is not sanctioned.');
        }
        return $this->sanctionNumber;
    }

    /**
     * @param string $sanctionNumber
     * @return $this
     */
    public function setSanctionNumber(string $sanctionNumber): self
    {
        Assert::lengthBetween($sanctionNumber, 1, 16);
        $this->sanctionNumber = $sanctionNumber;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasDate(): bool
    {
        return $this->startDate !== null && $this->endDate !== null;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getStartDate(): \DateTimeImmutable
    {
        if (!$this->hasDate()) {
            throw new \BadMethodCallException('Cannot get start date. No dates set.');
        }
        if (!DateTime::isTimeZoneEqual($this->startDate, $this->getTimeZone())) {
            $this->startDate = DateTime::reinterpret($this->startDate, $this->getTimeZone());
        }
        return $this->startDate;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getStartDateUtc(): \DateTimeImmutable
    {
        if (!DateTime::isUtc($this->startDateUtc)) {
            $this->startDateUtc = DateTime::reinterpretAsUtc($this->startDateUtc);
        }
        return $this->startDateUtc;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getEndDate(): \DateTimeImmutable
    {
        if (!$this->hasDate()) {
            throw new \BadMethodCallException('Cannot get end date. No dates set.');
        }
        if (!DateTime::isTimeZoneEqual($this->endDate, $this->getTimeZone())) {
            $this->endDate = DateTime::reinterpret($this->endDate, $this->getTimeZone());
        }
        return $this->endDate;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getEndDateUtc(): \DateTimeImmutable
    {
        if (!DateTime::isUtc($this->endDateUtc)) {
            $this->endDateUtc = DateTime::reinterpretAsUtc($this->endDateUtc);
        }
        return $this->endDateUtc;
    }

    /**
     * @param \DateTimeImmutable $startDate
     * @param \DateTimeImmutable $endDate
     * @param \DateTimeZone      $timeZone
     * @return $this
     */
    public function setDate(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate, \DateTimeZone $timeZone): self
    {
        Assert::lessThanEq($startDate, $endDate);
        Assert::greaterThanEq($endDate, $startDate);
        Assert::lengthBetween($timeZone->getName(), 1, 32);
        $startDate              = DateTime::reinterpret($startDate->setTime(0, 0, 0), $timeZone);
        $endDate                = DateTime::reinterpret($endDate->setTime(23, 59, 59), $timeZone);
        $this->startDate        = $startDate;
        $this->startDateUtc     = DateTime::toUtc($startDate);
        $this->endDate          = $endDate;
        $this->endDateUtc       = DateTime::toUtc($endDate);
        $this->timeZone         = $timeZone->getName();
        $this->timeZoneInstance = $timeZone;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasTimeZone(): bool
    {
        return $this->timeZone !== null;
    }

    /**
     * @return \DateTimeZone
     */
    public function getTimeZone(): \DateTimeZone
    {
        if (!$this->timeZone) {
            throw new \BadMethodCallException('Cannot get time zone. No time zone set.');
        }
        if (!$this->timeZoneInstance) {
            $this->timeZoneInstance = new \DateTimeZone($this->timeZone);
        }
        return $this->timeZoneInstance;
    }

    /**
     * @return string
     */
    public function getTimeZoneName(): string
    {
        return DateTime::formatTimeZoneName($this->getTimeZone());
    }

    /**
     * @return $this
     */
    public function clearDate(): self
    {
        $this->startDate        = null;
        $this->startDateUtc     = null;
        $this->endDate          = null;
        $this->endDateUtc       = null;
        $this->timeZone         = null;
        $this->timeZoneInstance = null;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasVenue(): bool
    {
        return $this->venue !== null;
    }

    /**
     * @return EventVenue
     */
    public function getVenue(): EventVenue
    {
        if (!$this->venue) {
            throw new \BadMethodCallException('Cannot get venue. No venue set.');
        }
        return $this->venue;
    }

    /**
     * @param EventVenue $venue
     * @return $this
     */
    public function setVenue(EventVenue $venue): self
    {
        $this->venue = $venue;
        return $this;
    }

    /**
     * @return $this
     */
    public function clearVenue(): self
    {
        $this->venue = null;
        return $this;
    }

    /**
     * @param string $id
     * @param string $name
     * @return ParticipatingTeam
     */
    public function createParticipant(string $id, string $name): ParticipatingTeam
    {
        return new ParticipatingTeam($id, $this, $name);
    }

    /**
     * @return string[]
     */
    public function getTags(): array
    {
        return $this->tags;
    }

    /**
     * @param string[] $tags
     * @return $this
     */
    public function setTags(array $tags): self
    {
        $collator = \Collator::create('en-US');
        $collator->sort($tags);
        $this->tags = array_unique($tags);
        return $this;
    }
}
