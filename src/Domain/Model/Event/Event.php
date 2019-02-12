<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-11
 * Time: 12:54
 */

namespace App\Domain\Model\Event;

use App\Domain\Common\AgeGroup;
use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\UpdateTracking;
use App\Domain\Model\Event\Team\ParticipatingTeam;
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
    use CreateTracking, UpdateTracking;

    public const STATE_PLANNED    = 'planned';
    public const STATE_SANCTIONED = 'sanctioned';

    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     *
     * @var string
     */
    private $id;

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
     * @ORM\Column(name="start_date", type="date_immutable", nullable=true)
     *
     * @var \DateTimeImmutable|null
     */
    private $startDate;

    /**
     * @ORM\Column(name="end_date", type="date_immutable", nullable=true)
     *
     * @var \DateTimeImmutable|null
     */
    private $endDate;

    /**
     * @ORM\ManyToOne(targetEntity="EventVenue")
     * @ORM\JoinColumn(name="venue_id", referencedColumnName="id", nullable=true)
     *
     * @var EventVenue|null
     */
    private $venue;

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
        Assert::uuid($id);

        $this->id           = $id;
        $this->currentState = self::STATE_PLANNED;
        $this->setName($name)
             ->setSlug($slug)
             ->setSeason($season)
             ->setAgeGroup($ageGroup)
             ->setTags($tags)
             ->initCreateTracking()
             ->initUpdateTracking();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
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
     * @return EventHost|null
     */
    public function getHost(): ?EventHost
    {
        return $this->host;
    }

    /**
     * @param EventHost|null $host
     * @return $this
     */
    public function setHost(?EventHost $host): self
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return bool
     */
    public function hasHost(): bool
    {
        return $this->host !== null;
    }

    /**
     * @return string|null
     */
    public function getHostingClub(): ?string
    {
        if ($this->host) {
            return $this->host->getClub();
        }
        return null;
    }

    /**
     * @return string|null
     */
    public function getSanctionNumber(): ?string
    {
        return $this->sanctionNumber;
    }

    /**
     * @param string|null $sanctionNumber
     * @return $this
     */
    public function setSanctionNumber(?string $sanctionNumber): self
    {
        Assert::nullOrLengthBetween($sanctionNumber, 1, 16);
        $this->sanctionNumber = $sanctionNumber;
        return $this;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * @return \DateTimeImmutable|null
     */
    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->endDate;
    }

    /**
     * @return bool
     */
    public function hasDate(): bool
    {
        return $this->startDate !== null && $this->endDate !== null;
    }

    /**
     * @param \DateTimeImmutable $startDate
     * @param \DateTimeImmutable $endDate
     * @return $this
     */
    public function setDate(\DateTimeImmutable $startDate, \DateTimeImmutable $endDate): self
    {
        Assert::lessThanEq($startDate, $endDate);
        Assert::greaterThanEq($endDate, $startDate);
        $this->startDate = $startDate;
        $this->endDate   = $endDate;
        return $this;
    }

    /**
     * @return $this
     */
    public function clearDate(): self
    {
        $this->startDate = null;
        $this->endDate   = null;
        return $this;
    }

    /**
     * @return EventVenue|null
     */
    public function getVenue(): ?EventVenue
    {
        return $this->venue;
    }

    /**
     * @param EventVenue|null $venue
     * @return $this
     */
    public function setVenue(?EventVenue $venue): self
    {
        $this->venue = $venue;
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
     * @return \DateTimeZone|null
     */
    public function getTimeZone(): ?\DateTimeZone
    {
        if (!$this->timeZoneInstance && $this->timeZone) {
            $this->timeZoneInstance = new \DateTimeZone($this->timeZone);
        }
        return $this->timeZoneInstance;
    }

    /**
     * @param \DateTimeZone|null $timeZone
     * @return $this
     */
    public function setTimeZone(?\DateTimeZone $timeZone): self
    {
        if ($timeZone) {
            Assert::lengthBetween($timeZone->getName(), 1, 32);
            $this->timeZone = $timeZone->getName();
        } else {
            $this->timeZone = null;
        }
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
