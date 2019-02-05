<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-05
 * Time: 07:14
 */

namespace App\Domain\Model\HallOfFame;

use App\Domain\Common\AgeGroup;
use App\Domain\Common\Country;
use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\UpdateTracking;
use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class HallOfFameEntry
 *
 * @package App\Domain\Model\HallOfFame
 *
 * @ORM\Entity(repositoryClass="HallOfFameRepository")
 * @ORM\Table(
 *      name="hall_of_fame",
 *      indexes={
 *          @ORM\Index(name="idx_season_age_group", columns={"season", "age_group"}),
 *          @ORM\Index(name="idx_age_group_season", columns={"age_group", "season"})
 *      }
 * )
 */
class HallOfFameEntry
{
    use CreateTracking, UpdateTracking;

    /**
     * @ORM\Column(name="id", type="guid")
     * @ORM\Id
     *
     * @var string
     */
    private $id;

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
     * @ORM\Column(name="event_date", type="date_immutable", nullable=true)
     *
     * @var \DateTimeImmutable|null
     */
    private $eventDate;

    /**
     * @ORM\Column(name="championship", type="boolean")
     *
     * @var bool
     */
    private $championship;

    /**
     * @ORM\Column(name="winner_club", type="string", length=128)
     *
     * @var string
     */
    private $winnerClub;

    /**
     * @ORM\Column(name="winner_country", type="string", length=2)
     *
     * @var string
     */
    private $winnerCountry;

    /**
     * @ORM\Column(name="club", type="string", length=128, nullable=true)
     *
     * @var string|null
     */
    private $hostClub;

    /**
     * @ORM\Column(name="host_country", type="string", length=2, nullable=true)
     *
     * @var string|null
     */
    private $hostCountry;

    /**
     * @param string                  $id
     * @param int                     $season
     * @param string                  $ageGroup
     * @param \DateTimeImmutable|null $eventDate
     * @param string                  $winnerClub
     * @param string                  $winnerCountry
     * @param string|null             $hostClub
     * @param string|null             $hostCountry
     * @return self
     */
    public static function forChampionship(
        string $id,
        int $season,
        string $ageGroup,
        ?\DateTimeImmutable $eventDate,
        string $winnerClub,
        string $winnerCountry,
        ?string $hostClub,
        ?string $hostCountry
    ): self {
        return new self(
            $id, $season, $ageGroup, $eventDate, true, $winnerClub, $winnerCountry, $hostClub, $hostCountry
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
     * @return self
     */
    public static function forCup(
        string $id,
        int $season,
        string $ageGroup,
        ?\DateTimeImmutable $eventDate,
        string $winnerClub,
        string $winnerCountry,
        ?string $hostClub,
        ?string $hostCountry
    ): self {
        return new self(
            $id, $season, $ageGroup, $eventDate, false, $winnerClub, $winnerCountry, $hostClub, $hostCountry
        );
    }

    /**
     * HallOfFameEntry constructor.
     *
     * @param string                  $id
     * @param int                     $season
     * @param string                  $ageGroup
     * @param \DateTimeImmutable|null $eventDate
     * @param bool                    $championship
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
        bool $championship,
        string $winnerClub,
        string $winnerCountry,
        ?string $hostClub,
        ?string $hostCountry
    ) {
        Assert::uuid($id);

        $this->id           = $id;
        $this->championship = $championship;

        $this->setSeason($season)
             ->setAgeGroup($ageGroup)
             ->setEventDate($eventDate)
             ->setWinnerClub($winnerClub)
             ->setWinnerCountry($winnerCountry)
             ->setHostClub($hostClub)
             ->setHostCountry($hostCountry)
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
     * @return \DateTimeImmutable|null
     */
    public function getEventDate(): ?\DateTimeImmutable
    {
        return $this->eventDate;
    }

    /**
     * @param \DateTimeImmutable|null $eventDate
     * @return $this
     */
    public function setEventDate(?\DateTimeImmutable $eventDate): self
    {
        $this->eventDate = $eventDate;
        return $this;
    }

    /**
     * @return bool
     */
    public function isChampionship(): bool
    {
        return $this->championship;
    }

    /**
     * @return string
     */
    public function getWinnerClub(): string
    {
        return $this->winnerClub;
    }

    /**
     * @param string $winnerClub
     * @return $this
     */
    public function setWinnerClub(string $winnerClub): self
    {
        Assert::lengthBetween($winnerClub, 1, 128);
        $this->winnerClub = $winnerClub;
        return $this;
    }

    /**
     * @return string
     */
    public function getWinnerCountry(): string
    {
        return $this->winnerCountry;
    }

    /**
     * @return string
     */
    public function getWinnerCountryName(): string
    {
        return Country::getCountryNameByCode($this->winnerCountry);
    }

    /**
     * @param string $winnerCountry
     * @return $this
     */
    public function setWinnerCountry(string $winnerCountry): self
    {
        $winnerCountry = mb_strtoupper($winnerCountry, 'UTF-8');
        Assert::length($winnerCountry, 2);
        Country::assertValidCountry($winnerCountry);
        $this->winnerCountry = $winnerCountry;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHostClub(): ?string
    {
        return $this->hostClub;
    }

    /**
     * @param string|null $hostClub
     * @return $this
     */
    public function setHostClub(?string $hostClub): self
    {
        Assert::nullOrLengthBetween($hostClub, 1, 128);
        $this->hostClub = $hostClub;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHostCountry(): ?string
    {
        return $this->hostCountry;
    }

    /**
     * @return string|null
     */
    public function getHostCountryName(): ?string
    {
        return $this->hostCountry ? Country::getCountryNameByCode($this->hostCountry) : null;
    }

    /**
     * @param string|null $hostCountry
     * @return $this
     */
    public function setHostCountry(?string $hostCountry): self
    {
        if ($hostCountry !== null) {
            $hostCountry = mb_strtoupper($hostCountry, 'UTF-8');
            Assert::length($hostCountry, 2);
            Country::assertValidCountry($hostCountry);
            $this->hostCountry = $hostCountry;
        } else {
            $this->hostCountry = null;
        }
        return $this;
    }
}
