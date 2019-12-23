<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 08:37
 */

namespace App\Domain\Common;

use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use InvalidArgumentException;

/**
 * Class DateTime
 *
 * @package App\Domain\Common
 */
final class DateTime
{
    /**
     */
    private function __construct()
    {
        // static class - left empty
    }

    /**
     * @param DateTimeImmutable $dateTime
     * @return DateTimeImmutable
     */
    public static function toUtc(DateTimeImmutable $dateTime): DateTimeImmutable
    {
        return self::toTimeZone($dateTime, Timezone::getUtc());
    }

    /**
     * @param DateTimeImmutable $dateTime
     * @param DateTimeZone      $toTimeZone
     * @return DateTimeImmutable
     */
    public static function toTimeZone(DateTimeImmutable $dateTime, DateTimeZone $toTimeZone): DateTimeImmutable
    {
        return $dateTime->setTimezone($toTimeZone);
    }

    /**
     * @param DateTimeInterface $dateTime
     * @param DateTimeZone      $inTimeZone
     * @return DateTimeImmutable
     */
    public static function reinterpret(DateTimeInterface $dateTime, DateTimeZone $inTimeZone): DateTimeImmutable
    {
        return new DateTimeImmutable($dateTime->format('Y-m-d H:i:s.u'), $inTimeZone);
    }

    /**
     * @param DateTimeInterface $dateTime
     * @return DateTimeImmutable
     */
    public static function reinterpretAsUtc(DateTimeInterface $dateTime): DateTimeImmutable
    {
        return new DateTimeImmutable($dateTime->format('Y-m-d H:i:s.u'), Timezone::getUtc());
    }

    /**
     * @param DateTimeZone|DateTimeInterface $a
     * @param DateTimeZone|DateTimeInterface $b
     * @return bool
     */
    public static function isTimeZoneEqual($a, $b): bool
    {
        if ($a instanceof DateTimeInterface) {
            $a = $a->getTimezone();
        }
        if ($b instanceof DateTimeInterface) {
            $b = $b->getTimezone();
        }
        if (!$a instanceof DateTimeZone || !$b instanceof DateTimeZone) {
            throw new InvalidArgumentException(
                'Parameters must either be instances of ' . DateTimeZone::class . ' or ' . DateTimeInterface::class . '.'
            );
        }
        return $a->getName() === $b->getName();
    }

    /**
     * @param DateTimeZone|DateTimeInterface $dateTimeOrTimeZone
     * @return bool
     */
    public static function isUtc($dateTimeOrTimeZone): bool
    {
        if ($dateTimeOrTimeZone instanceof DateTimeInterface) {
            $dateTimeOrTimeZone = $dateTimeOrTimeZone->getTimezone();
        }
        if (!$dateTimeOrTimeZone instanceof DateTimeZone) {
            throw new InvalidArgumentException(
                'Parameter must either be instance of ' . DateTimeZone::class . ' or ' . DateTimeInterface::class . '.'
            );
        }
        return $dateTimeOrTimeZone->getName() === 'UTC';
    }
}
