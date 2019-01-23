<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-14
 * Time: 12:43
 */

namespace App\Application\Event\Command;

use App\Application\Event\EventHost;
use App\Domain\Model\Event\EventVenue;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait TitleEventProperties
 *
 * @package App\Application\Event\Command
 */
trait TitleEventProperties
{
    /**
     * @Assert\Type("integer")
     * @Assert\Range(min=1, max=31)
     * @Assert\NotBlank()
     *
     * @var int
     */
    private $plannedLength = 2;

    /**
     * @Assert\Type("string")
     * @Assert\Length(max=65535)
     *
     * @var string|null
     */
    private $description;

    /**
     * @Assert\Type("App\Application\Event\EventHost")
     * @Assert\Expression(
     *      expression="not this.isAnnounced() ? value === null : true",
     *      message="This value must be empty while the event is not announced yet."
     * )
     * @Assert\Valid()
     *
     * @var EventHost|null
     */
    private $host;

    /**
     * @Assert\Type("DateTimeImmutable")
     * @Assert\Expression(
     *      expression="not this.isAnnounced() ? value === null : true",
     *      message="This value must be empty while the event is not announced yet."
     * )
     * @Assert\Expression(
     *      expression="this.isAnnounced() ? value !== null : true",
     *      message="This value must not be empty when the event is already announced."
     * )
     * @Assert\Expression(
     *      expression="value === null or this.getEndDate() === null or value <= this.getEndDate()",
     *      message="This value should be less than or equal to the end date."
     * )
     *
     * @var \DateTimeImmutable|null
     */
    private $startDate;

    /**
     * @Assert\Type("DateTimeImmutable")
     * @Assert\Expression(
     *      expression="not this.isAnnounced() ? value === null : true",
     *      message="This value must be empty while the event is not announced yet."
     * )
     * @Assert\Expression(
     *      expression="this.isAnnounced() ? value !== null : true",
     *      message="This value must not be empty when the event is already announced."
     * )
     * @Assert\Expression(
     *      expression="value === null or this.getStartDate() === null or value >= this.getStartDate()",
     *      message="This value should be greater than or equal to the start date."
     * )
     *
     * @var \DateTimeImmutable|null
     */
    private $endDate;

    /**
     * @Assert\Type("App\Domain\Model\Event\EventVenue")
     * @Assert\Expression(
     *      expression="not this.isAnnounced() ? value === null : true",
     *      message="This value must be empty while the event is not announced yet."
     * )
     *
     * @var EventVenue|null
     */
    private $venue;

    /**
     * @var bool
     */
    private $announced = false;

    /**
     * @return int
     */
    public function getPlannedLength(): int
    {
        return $this->plannedLength;
    }

    /**
     * @param int $plannedLength
     * @return $this
     */
    public function setPlannedLength(int $plannedLength): self
    {
        $this->plannedLength = $plannedLength;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return $this
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return bool
     */
    public function isAnnounced(): bool
    {
        return $this->announced;
    }

    /**
     * @return EventHost
     */
    public function getHost(): EventHost
    {
        if (!$this->host || !$this->isAnnounced()) {
            throw new \BadMethodCallException('Cannot get the host of an unannounced event.');
        }
        return $this->host;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getStartDate(): \DateTimeImmutable
    {
        if (!$this->startDate || !$this->isAnnounced()) {
            throw new \BadMethodCallException('Cannot get the start date of an unannounced event.');
        }
        return $this->startDate;
    }

    /**
     * @param \DateTimeImmutable $startDate
     * @return $this
     */
    public function setStartDate(\DateTimeImmutable $startDate): self
    {
        if (!$this->isAnnounced()) {
            throw new \BadMethodCallException('Cannot set the start date of an unannounced event.');
        }
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getEndDate(): \DateTimeImmutable
    {
        if (!$this->endDate || !$this->isAnnounced()) {
            throw new \BadMethodCallException('Cannot get the end date of an unannounced event.');
        }
        return $this->endDate;
    }

    /**
     * @param \DateTimeImmutable $endDate
     * @return $this
     */
    public function setEndDate(\DateTimeImmutable $endDate): self
    {
        if (!$this->isAnnounced()) {
            throw new \BadMethodCallException('Cannot set the end date of an unannounced event.');
        }
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return EventVenue
     */
    public function getVenue(): EventVenue
    {
        if (!$this->venue || !$this->isAnnounced()) {
            throw new \BadMethodCallException('Cannot get the venue of an unannounced event.');
        }
        return $this->venue;
    }

    /**
     * @param EventVenue $venue
     * @return $this
     */
    public function setVenue(EventVenue $venue): self
    {
        if (!$this->isAnnounced()) {
            throw new \BadMethodCallException('Cannot set the venue of an unannounced event.');
        }
        $this->venue = $venue;
        return $this;
    }
}
