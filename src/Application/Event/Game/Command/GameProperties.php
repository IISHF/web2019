<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 09:00
 */

namespace App\Application\Event\Game\Command;

use App\Application\Event\Game\Validator\GameType as ValidGameType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait GameProperties
 *
 * @package App\Application\Event\Game\Command
 */
trait GameProperties
{
    /**
     * @Assert\Type("integer")
     * @Assert\NotBlank()
     * @ValidGameType()
     *
     * @var int
     */
    private $gameType;

    /**
     * @Assert\Type("DateTimeImmutable")
     * @Assert\NotNull()
     *
     * @var \DateTimeImmutable
     */
    private $dateTime;

    /**
     * @Assert\Type("string")
     * @Assert\Uuid()
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $homeTeam;

    /**
     * @Assert\Type("string")
     * @Assert\Uuid()
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $awayTeam;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     *
     * @var string|null
     */
    private $remarks;

    /**
     * @Assert\Type("integer")
     * @Assert\Range(min=0, max=99)
     * @Assert\Expression(
     *      expression="value === null ? this.getAwayGoals() === null : this.getAwayGoals() !== null",
     *      message="Either both home goals and away goals must be filled in or both must be empty."
     * )
     *
     * @var int|null
     */
    private $homeGoals;

    /**
     * @Assert\Type("integer")
     * @Assert\Range(min=0, max=99)
     * @Assert\Expression(
     *      expression="value === null ? this.getHomeGoals() === null : this.getHomeGoals() !== null",
     *      message="Either both home goals and away goals must be filled in or both must be empty."
     * )
     *
     * @var int|null
     */
    private $awayGoals;

    /**
     * @return int
     */
    public function getGameType(): int
    {
        return $this->gameType;
    }

    /**
     * @param int $gameType
     * @return $this
     */
    public function setGameType(int $gameType): self
    {
        $this->gameType = $gameType;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDateTime(): \DateTimeImmutable
    {
        return $this->dateTime;
    }

    /**
     * @param \DateTimeImmutable $dateTime
     * @return $this
     */
    public function setDateTime(\DateTimeImmutable $dateTime): self
    {
        $this->dateTime = $dateTime;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHomeTeam(): ?string
    {
        return $this->homeTeam;
    }

    /**
     * @param string $homeTeam
     * @return $this
     */
    public function setHomeTeam(string $homeTeam): self
    {
        $this->homeTeam = $homeTeam;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAwayTeam(): ?string
    {
        return $this->awayTeam;
    }

    /**
     * @param string $awayTeam
     * @return $this
     */
    public function setAwayTeam(string $awayTeam): self
    {
        $this->awayTeam = $awayTeam;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRemarks(): ?string
    {
        return $this->remarks;
    }

    /**
     * @param string|null $remarks
     * @return $this
     */
    public function setRemarks(?string $remarks): self
    {
        $this->remarks = $remarks;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getHomeGoals(): ?int
    {
        return $this->homeGoals;
    }

    /**
     * @param int|null $homeGoals
     * @return $this
     */
    public function setHomeGoals(?int $homeGoals): self
    {
        $this->homeGoals = $homeGoals;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAwayGoals(): ?int
    {
        return $this->awayGoals;
    }

    /**
     * @param int|null $awayGoals
     * @return $this
     */
    public function setAwayGoals(?int $awayGoals): self
    {
        $this->awayGoals = $awayGoals;
        return $this;
    }
}
