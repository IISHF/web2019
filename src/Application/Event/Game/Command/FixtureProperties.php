<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-14
 * Time: 12:27
 */

namespace App\Application\Event\Game\Command;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait FixtureProperties
 *
 * @package App\Application\Event\Game\Command
 */
trait FixtureProperties
{
    /**
     * @Assert\Type("bool")
     * @Assert\NotNull();
     *
     * @var bool
     */
    private $homeTeamIsProvisional = false;

    /**
     * @Assert\Type("string")
     * @Assert\Uuid()
     * @Assert\NotIdenticalTo(
     *      propertyPath="awayTeam",
     *      message="The home team cannot be the same team as the away team."
     * )
     * @Assert\NotIdenticalTo(
     *      propertyPath="awayTeamProvisional",
     *      message="The home team cannot be the same team as the away team."
     * )
     * @Assert\Expression(
     *      expression="this.getHomeTeamIsProvisional() ? true : value !== null",
     *      message="The team must not be empty when the team is not provisional."
     * )
     *
     * @var string|null
     */
    private $homeTeam;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=64)
     * @Assert\NotIdenticalTo(
     *      propertyPath="awayTeam",
     *      message="The home team cannot be the same team as the away team."
     * )
     * @Assert\NotIdenticalTo(
     *      propertyPath="awayTeamProvisional",
     *      message="The home team cannot be the same team as the away team."
     * )
     * @Assert\Expression(
     *      expression="this.getHomeTeamIsProvisional() ? value !== null : true",
     *      message="The team must not be empty when the team is provisional."
     * )
     *
     * @var string|null
     */
    private $homeTeamProvisional;

    /**
     * @Assert\Type("bool")
     * @Assert\NotNull();
     *
     * @var bool
     */
    private $awayTeamIsProvisional = false;

    /**
     * @Assert\Type("string")
     * @Assert\Uuid()
     * @Assert\NotIdenticalTo(
     *      propertyPath="homeTeam",
     *      message="The away team cannot be the same team as the home team."
     * )
     * @Assert\NotIdenticalTo(
     *      propertyPath="homeTeamProvisional",
     *      message="The away team cannot be the same team as the home team."
     * )
     * @Assert\Expression(
     *      expression="this.getAwayTeamIsProvisional() ? true : value !== null",
     *      message="The team must not be empty when the team is not provisional."
     * )
     *
     * @var string|null
     */
    private $awayTeam;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=64)
     * @Assert\NotIdenticalTo(
     *      propertyPath="homeTeam",
     *      message="The away team cannot be the same team as the home team."
     * )
     * @Assert\NotIdenticalTo(
     *      propertyPath="homeTeamProvisional",
     *      message="The away team cannot be the same team as the home team."
     * )
     * @Assert\Expression(
     *      expression="this.getAwayTeamIsProvisional() ? value !== null : true",
     *      message="The team must not be empty when the team is provisional."
     * )
     *
     * @var string|null
     */
    private $awayTeamProvisional;

    /**
     * @param string|null $homeTeam
     * @param string|null $homeTeamProvisional
     * @param string|null $awayTeam
     * @param string|null $awayTeamProvisional
     * @return $this
     */
    private function initFixture(
        ?string $homeTeam,
        ?string $homeTeamProvisional,
        ?string $awayTeam,
        ?string $awayTeamProvisional
    ): self {
        $this->homeTeamIsProvisional = $homeTeam === null;
        $this->homeTeam              = $homeTeam;
        $this->homeTeamProvisional   = $homeTeamProvisional;
        $this->awayTeamIsProvisional = $awayTeam === null;
        $this->awayTeam              = $awayTeam;
        $this->awayTeamProvisional   = $awayTeamProvisional;
        return $this;
    }

    /**
     * @return bool
     */
    public function getHomeTeamIsProvisional(): bool
    {
        return $this->homeTeamIsProvisional;
    }

    /**
     * @param bool $homeTeamIsProvisional
     * @return $this
     */
    public function setHomeTeamIsProvisional(bool $homeTeamIsProvisional): self
    {
        $this->homeTeamIsProvisional = $homeTeamIsProvisional;
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
     * @param string|null $homeTeam
     * @return $this
     */
    public function setHomeTeam(?string $homeTeam): self
    {
        $this->homeTeam = $homeTeam;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHomeTeamProvisional(): ?string
    {
        return $this->homeTeamProvisional;
    }

    /**
     * @param string|null $homeTeamProvisional
     * @return self
     */
    public function setHomeTeamProvisional(?string $homeTeamProvisional): self
    {
        $this->homeTeamProvisional = $homeTeamProvisional;
        return $this;
    }

    /**
     * @return bool
     */
    public function getAwayTeamIsProvisional(): bool
    {
        return $this->awayTeamIsProvisional;
    }

    /**
     * @param bool $awayTeamIsProvisional
     * @return $this
     */
    public function setAwayTeamIsProvisional(bool $awayTeamIsProvisional): self
    {
        $this->awayTeamIsProvisional = $awayTeamIsProvisional;
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
     * @param string|null $awayTeam
     * @return $this
     */
    public function setAwayTeam(?string $awayTeam): self
    {
        $this->awayTeam = $awayTeam;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAwayTeamProvisional(): ?string
    {
        return $this->awayTeamProvisional;
    }

    /**
     * @param string|null $awayTeamProvisional
     * @return self
     */
    public function setAwayTeamProvisional(?string $awayTeamProvisional): self
    {
        $this->awayTeamProvisional = $awayTeamProvisional;
        return $this;
    }
}
