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
     * @Assert\Type("string")
     * @Assert\Uuid()
     * @Assert\NotBlank()
     * @Assert\NotIdenticalTo(
     *      propertyPath="awayTeam",
     *      message="The home team cannot be the same team as the away team."
     * )
     *
     * @var string
     */
    private $homeTeam;

    /**
     * @Assert\Type("string")
     * @Assert\Uuid()
     * @Assert\NotBlank()
     * @Assert\NotIdenticalTo(
     *      propertyPath="homeTeam",
     *      message="The away team cannot be the same team as the home team."
     * )
     *
     * @var string
     */
    private $awayTeam;

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
}
