<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-14
 * Time: 07:13
 */

namespace App\Application\Event\Command;

use App\Application\Event\EventHost;
use DateTimeImmutable;
use DateTimeZone;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait AnnouncedTitleEventProperties
 *
 * @package App\Application\Event\Command
 */
trait HostingProperties
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
     * @var DateTimeImmutable
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
     * @var DateTimeImmutable
     */
    private $endDate;

    /**
     * @Assert\Type("string")
     * @Assert\Uuid()
     * @Assert\NotBlank()
     *
     * @var string
     */
    private $venue;

    /**
     * @Assert\Type("DateTimeZone")
     * @Assert\NotNull()
     *
     * @var DateTimeZone
     */
    private $timeZone;

    /**
     * @return EventHost
     */
    public function getHost(): EventHost
    {
        return $this->host;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getStartDate(): DateTimeImmutable
    {
        return $this->startDate;
    }

    /**
     * @param DateTimeImmutable $startDate
     * @return $this
     */
    public function setStartDate(DateTimeImmutable $startDate): self
    {
        $this->startDate = $startDate;
        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getEndDate(): DateTimeImmutable
    {
        return $this->endDate;
    }

    /**
     * @param DateTimeImmutable $endDate
     * @return $this
     */
    public function setEndDate(DateTimeImmutable $endDate): self
    {
        $this->endDate = $endDate;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVenue(): ?string
    {
        return $this->venue;
    }

    /**
     * @param string $venue
     * @return $this
     */
    public function setVenue(string $venue): self
    {
        $this->venue = $venue;
        return $this;
    }

    /**
     * @return DateTimeZone|null
     */
    public function getTimeZone(): ?DateTimeZone
    {
        return $this->timeZone;
    }

    /**
     * @param DateTimeZone $timeZone
     * @return $this
     */
    public function setTimeZone(DateTimeZone $timeZone): self
    {
        $this->timeZone = $timeZone;
        return $this;
    }
}
