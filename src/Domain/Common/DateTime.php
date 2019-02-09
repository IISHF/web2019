<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 08:37
 */

namespace App\Domain\Common;

/**
 * Class DateTime
 *
 * @package App\Domain\Common
 */
final class DateTime
{

    /**
     * @var \DateTimeZone|null
     */
    private static $utc;

    /**
     */
    private function __construct()
    {
        // static class - left empty
    }

    /**
     * @return \DateTimeZone
     */
    public static function utc(): \DateTimeZone
    {
        if (!self::$utc) {
            self::$utc = new \DateTimeZone('UTC');
        }
        return self::$utc;
    }

    /**
     * @param \DateTimeImmutable $dateTime
     * @return \DateTimeImmutable
     */
    public static function toUtc(\DateTimeImmutable $dateTime): \DateTimeImmutable
    {
        return self::toTimeZone($dateTime, self::utc());
    }

    /**
     * @param \DateTimeImmutable $dateTime
     * @param \DateTimeZone      $toTimeZone
     * @return \DateTimeImmutable
     */
    public static function toTimeZone(\DateTimeImmutable $dateTime, \DateTimeZone $toTimeZone): \DateTimeImmutable
    {
        return $dateTime->setTimezone($toTimeZone);
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @param \DateTimeZone      $inTimeZone
     * @return \DateTimeImmutable
     */
    public static function reinterpret(\DateTimeInterface $dateTime, \DateTimeZone $inTimeZone): \DateTimeImmutable
    {
        return new \DateTimeImmutable($dateTime->format('Y-m-d H:i:s.u'), $inTimeZone);
    }

    /**
     * @param \DateTimeInterface $dateTime
     * @return \DateTimeImmutable
     */
    public static function reinterpretAsUtc(\DateTimeInterface $dateTime): \DateTimeImmutable
    {
        return new \DateTimeImmutable($dateTime->format('Y-m-d H:i:s.u'), self::utc());
    }
}
