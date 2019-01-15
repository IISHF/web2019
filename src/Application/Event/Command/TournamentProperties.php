<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 12:38
 */

namespace App\Application\Event\Command;

use App\Application\Event\EventHost;
use App\Domain\Model\Event\EventVenue;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait TournamentProperties
 *
 * @package App\Application\Event\Command
 */
trait TournamentProperties
{
    /**
     * @Assert\Type("App\Application\Event\EventHost")
     * @Assert\NotNull()
     * @Assert\Valid()
     *
     * @var EventHost
     */
    private $host;

    /**
     * @Assert\Type("DateTimeImmutable")
     * @Assert\NotNull()
     * @Assert\LessThanOrEqual(
     *      propertyPath="endDate",
     *      message="This value should be less than or equal to the end date."
     * )
     *
     * @var \DateTimeImmutable
     */
    private $startDate;

    /**
     * @Assert\Type("DateTimeImmutable")
     * @Assert\NotNull()
     * @Assert\GreaterThanOrEqual(
     *      propertyPath="startDate",
     *      message="This value should be greater than or equal to the start date."
     * )
     *
     * @var \DateTimeImmutable
     */
    private $endDate;

    /**
     * @Assert\Type("App\Domain\Model\Event\EventVenue")
     * @Assert\NotNull()
     *
     * @var EventVenue|null
     */
    private $venue;

    /**
     * @return EventHost
     */
    public function getHost(): EventHost
    {
        return $this->host;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getStartDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * @param \DateTimeImmutable $startDate
     * @return $this
     */
    public function setStartDate(\DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getEndDate(): \DateTimeImmutable
    {
        return $this->endDate;
    }

    /**
     * @param \DateTimeImmutable $endDate
     * @return $this
     */
    public function setEndDate(\DateTimeImmutable $endDate): self
    {
        $this->endDate = $endDate;
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
     * @param EventVenue $venue
     * @return $this
     */
    public function setVenue(EventVenue $venue): self
    {
        $this->venue = $venue;
        return $this;
    }
}
