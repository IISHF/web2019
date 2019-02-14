<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 12:46
 */

namespace App\Application\Event\Command\EuropeanCup;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait EuropeanCupProperties
 *
 * @package App\Application\Event\Command\EuropeanCup
 */
trait EuropeanCupProperties
{
    /**
     * @Assert\Type("integer")
     * @Assert\Range(min=2, max=99)
     * @Assert\NotBlank()
     *
     * @var int
     */
    private $plannedTeams = 10;

    /**
     * @return int
     */
    public function getPlannedTeams(): int
    {
        return $this->plannedTeams;
    }

    /**
     * @param int $plannedTeams
     * @return $this
     */
    public function setPlannedTeams(int $plannedTeams): self
    {
        $this->plannedTeams = $plannedTeams;
        return $this;
    }
}
