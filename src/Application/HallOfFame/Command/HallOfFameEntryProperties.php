<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-05
 * Time: 07:46
 */

namespace App\Application\HallOfFame\Command;

use App\Application\Common\Validator\AgeGroup as ValidAgeGroup;
use App\Domain\Common\AgeGroup;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait HallOfFameEntryProperties
 *
 * @package App\Application\HallOfFame\Command
 */
trait HallOfFameEntryProperties
{
    /**
     * @Assert\Type("integer")
     * @Assert\Range(min=1990, max=9999)
     * @Assert\NotBlank()
     *
     * @var int
     */
    private $season;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=16)
     * @Assert\NotBlank()
     * @ValidAgeGroup()
     *
     * @var string
     */
    private $ageGroup = AgeGroup::AGE_GROUP_MEN;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $event = '';

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=64)
     *
     * @var string|null
     */
    private $eventDate;

    /**
     * @Assert\Type("bool")
     * @Assert\NotNull()
     *
     * @var bool
     */
    private $championship = false;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $winnerClub = '';

    /**
     * @Assert\Type("string")
     * @Assert\Country()
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $winnerCountry = '';

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=128)
     * @Assert\Expression(
     *      expression="value === null ? this.getHostCountry() === null : this.getHostCountry()",
     *      message="Either both the hosting club and its country must be filled in or both must be empty."
     * )
     *
     * @var string|null
     */
    private $hostClub;

    /**
     * @Assert\Type("string")
     * @Assert\Country()
     * @Assert\Expression(
     *      expression="value === null ? this.getHostClub() === null : this.getHostClub() !== null",
     *      message="Either both the hosting club and its country must be filled in or both must be empty."
     * )
     *
     * @var string|null
     */
    private $hostCountry;

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
        $this->ageGroup = $ageGroup;
        return $this;
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
     * @param string $winnerCountry
     * @return $this
     */
    public function setWinnerCountry(string $winnerCountry): self
    {
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
     * @param string|null $hostCountry
     * @return $this
     */
    public function setHostCountry(?string $hostCountry): self
    {
        $this->hostCountry = $hostCountry;
        return $this;
    }
}
