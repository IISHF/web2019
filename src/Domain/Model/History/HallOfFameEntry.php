<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-05
 * Time: 07:14
 */

namespace App\Domain\Model\History;

use App\Domain\Common\AgeGroup;
use App\Domain\Common\Country;
use App\Domain\Model\Common\CreateTracking;
use App\Domain\Model\Common\HasId;
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
 *          @ORM\Index(name="idx_season_age_group", columns={"season", "championship", "age_group"}),
 *          @ORM\Index(name="idx_age_group_season", columns={"championship", "age_group", "season"})
 *      }
 * )
 */
class HallOfFameEntry
{
    use HasId, CreateTracking, UpdateTracking;

    /**
     * @ORM\Column(name="season", type="smallint", options={"unsigned": true})
     *
     * @var int
     */
    private int $season;

    /**
     * @ORM\Column(name="age_group", type="string", length=16)
     *
     * @var string
     */
    private string $ageGroup;

    /**
     * @ORM\Column(name="event", type="string", length=128)
     *
     * @var string
     */
    private string $event;

    /**
     * @ORM\Column(name="event_date", type="string", length=64, nullable=true)
     *
     * @var string|null
     */
    private ?string $eventDate;

    /**
     * @ORM\Column(name="championship", type="boolean")
     *
     * @var bool
     */
    private bool $championship;

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
    private string $winnerCountry;

    /**
     * @ORM\Column(name="second_place_club", type="string", length=128, nullable=true)
     *
     * @var string|null
     */
    private ?string $secondPlaceClub;

    /**
     * @ORM\Column(name="second_place_country", type="string", length=2, nullable=true)
     *
     * @var string|null
     */
    private ?string $secondPlaceCountry;

    /**
     * @ORM\Column(name="third_place_club", type="string", length=128, nullable=true)
     *
     * @var string|null
     */
    private ?string $thirdPlaceClub;

    /**
     * @ORM\Column(name="third_place_country", type="string", length=2, nullable=true)
     *
     * @var string|null
     */
    private ?string $thirdPlaceCountry;

    /**
     * @ORM\Column(name="host_club", type="string", length=128, nullable=true)
     *
     * @var string|null
     */
    private ?string $hostClub;

    /**
     * @ORM\Column(name="host_country", type="string", length=2, nullable=true)
     *
     * @var string|null
     */
    private ?string $hostCountry;

    /**
     * @param string      $id
     * @param int         $season
     * @param string      $ageGroup
     * @param string      $event
     * @param string|null $eventDate
     * @param bool        $championship
     * @param string      $winnerClub
     * @param string      $winnerCountry
     * @param string|null $hostClub
     * @param string|null $hostCountry
     */
    public function __construct(
        string $id,
        int $season,
        string $ageGroup,
        string $event,
        ?string $eventDate,
        bool $championship,
        string $winnerClub,
        string $winnerCountry,
        ?string $hostClub,
        ?string $hostCountry
    ) {
        $this->setId($id)
             ->setSeason($season)
             ->setAgeGroup($ageGroup)
             ->setEvent($event)
             ->setEventDate($eventDate)
             ->setChampionship($championship)
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
    public function getCaption(): string
    {
        return sprintf('%d %s %s', $this->season, $this->getAgeGroupName(), $this->event);
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
        Assert::range($season, 1990, 9999);
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
    public function getAgeGroupName(): string
    {
        return AgeGroup::getAgeGroupName($this->ageGroup, 'unknown');
    }

    /**
     * @return string
     */
    public function getEvent(): string
    {
        return $this->event;
    }

    /**
     * @param string $event
     * @return $this
     */
    public function setEvent(string $event): self
    {
        Assert::nullOrLengthBetween($event, 1, 128);
        $this->event = $event;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getEventDate(): ?string
    {
        return $this->eventDate;
    }

    /**
     * @param string|null $eventDate
     * @return $this
     */
    public function setEventDate(?string $eventDate): self
    {
        Assert::nullOrLengthBetween($eventDate, 1, 64);
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
     * @param bool $championship
     * @return $this
     */
    public function setChampionship(bool $championship): self
    {
        $this->championship = $championship;
        return $this;
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
    public function getSecondPlaceClub(): ?string
    {
        return $this->secondPlaceClub;
    }

    /**
     * @param string $secondPlaceClub
     * @return $this
     */
    public function setSecondPlaceClub(string $secondPlaceClub): self
    {
        Assert::nullOrLengthBetween($secondPlaceClub, 1, 128);
        $this->secondPlaceClub = $secondPlaceClub;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSecondPlaceCountry(): ?string
    {
        return $this->secondPlaceCountry;
    }

    /**
     * @return string|null
     */
    public function getSecondPlaceCountryName(): ?string
    {
        return $this->secondPlaceCountry ? Country::getCountryNameByCode($this->secondPlaceCountry) : null;
    }

    /**
     * @param string|null $secondPlaceCountry
     * @return $this
     */
    public function setSecondPlaceCountry(?string $secondPlaceCountry): self
    {
        if ($secondPlaceCountry !== null) {
            $secondPlaceCountry = mb_strtoupper($secondPlaceCountry, 'UTF-8');
            Assert::length($secondPlaceCountry, 2);
            Country::assertValidCountry($secondPlaceCountry);
            $this->secondPlaceCountry = $secondPlaceCountry;
        } else {
            $this->secondPlaceCountry = null;
        }
        return $this;
    }

    /**
     * @return string|null
     */
    public function getThirdPlaceClub(): ?string
    {
        return $this->thirdPlaceClub;
    }

    /**
     * @param string $thirdPlaceClub
     * @return $this
     */
    public function setThirdPlaceClub(string $thirdPlaceClub): self
    {
        Assert::nullOrLengthBetween($thirdPlaceClub, 1, 128);
        $this->thirdPlaceClub = $thirdPlaceClub;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getThirdPlaceCountry(): ?string
    {
        return $this->thirdPlaceCountry;
    }

    /**
     * @return string|null
     */
    public function getThirdPlaceCountryName(): ?string
    {
        return $this->thirdPlaceCountry ? Country::getCountryNameByCode($this->thirdPlaceCountry) : null;
    }

    /**
     * @param string|null $thirdPlaceCountry
     * @return $this
     */
    public function setThirdPlaceCountry(?string $thirdPlaceCountry): self
    {
        if ($thirdPlaceCountry !== null) {
            $thirdPlaceCountry = mb_strtoupper($thirdPlaceCountry, 'UTF-8');
            Assert::length($thirdPlaceCountry, 2);
            Country::assertValidCountry($thirdPlaceCountry);
            $this->thirdPlaceCountry = $thirdPlaceCountry;
        } else {
            $this->thirdPlaceCountry = null;
        }
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
