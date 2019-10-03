<?php
/**
 * LocalizedDateTime
 */

namespace App\Domain\Model\Common;

use App\Domain\Common\Timezone;
use BadMethodCallException;
use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class LocalizedDateTime
 *
 * @package App\Domain\Model\Common
 *
 * @ORM\Embeddable()
 */
class LocalizedDateTime
{
    /**
     * @ORM\Column(name="datetime_utc", type="datetime_utc", nullable=true)
     *
     * @var DateTime|null
     */
    private $dateTime;

    /**
     * @ORM\Column(name="time_zone", type="timezone", nullable=true)
     *
     * @var DateTimeZone|null
     */
    private $timezone;

    /**
     * @var bool
     */
    private $localized = false;

    /**
     * @param DateTimeImmutable $dateTime
     * @return self
     */
    public static function from(DateTimeImmutable $dateTime): self
    {
        return new self($dateTime);
    }

    /**
     * @return self
     */
    public static function null(): self
    {
        return new self(null);
    }

    /**
     * @param DateTimeImmutable|null $dateTime
     */
    private function __construct(?DateTimeImmutable $dateTime)
    {
        if ($dateTime) {
            $this->dateTime  = new DateTime(
                $dateTime->format('Y-m-d H:i:s.u'),
                $dateTime->getTimezone()
            );
            $this->timezone  = $this->dateTime->getTimezone();
            $this->localized = true;
        } else {
            $this->dateTime = null;
            $this->timezone = null;
        }
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function get(): ?DateTimeImmutable
    {
        if ($this->isNull()) {
            return null;
        }
        if (!$this->localized) {
            $this->localized = true;
            $this->dateTime->setTimezone($this->timezone);
        }
        return DateTimeImmutable::createFromMutable($this->dateTime);
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function utc(): ?DateTimeImmutable
    {
        if ($this->isNull()) {
            return null;
        }

        return $this->get()->setTimezone(Timezone::getUtc());
    }

    /**
     * @return DateTimeZone
     */
    public function getTimezone(): DateTimeZone
    {
        if (!$this->timezone) {
            throw new BadMethodCallException('Cannot call ' . __METHOD__ . ' when data is null.');
        }
        return $this->timezone;
    }

    /**
     * @return string|null
     */
    public function getTimezoneId(): ?string
    {
        if ($this->isNull()) {
            return null;
        }

        return $this->getTimezone()->getName();
    }

    /**
     * @return string|null
     */
    public function getTimezoneName(): ?string
    {
        if ($this->isNull()) {
            return null;
        }

        return Timezone::getName($this->getTimezone());
    }

    /**
     * @return bool
     */
    public function isNull(): bool
    {
        return $this->dateTime === null || $this->timezone === null;
    }
}
