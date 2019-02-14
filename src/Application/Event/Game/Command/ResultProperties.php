<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-14
 * Time: 12:22
 */

namespace App\Application\Event\Game\Command;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait ResultProperties
 *
 * @package App\Application\Event\Game\Command
 */
trait ResultProperties
{
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
