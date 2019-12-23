<?php
/**
 * Timezone
 */

namespace App\Domain\Common;


use DateTimeImmutable;
use DateTimeZone;
use Webmozart\Assert\Assert;

/**
 * Class Timezone
 *
 * @package App\Domain\Common
 */
final class Timezone
{
    public const DEFAULT = 'UTC';

    /**
     * @var DateTimeZone|null
     */
    static private $utc;

    /**
     * @var DateTimeZone[]
     */
    private static $timezoneMap = [];

    /**
     * @return DateTimeZone
     */
    public static function getUtc(): DateTimeZone
    {
        return self::$utc ?: self::$utc = new DateTimeZone('UTC');
    }

    /**
     * @param DateTimeZone|string|null $timezone
     * @return DateTimeZone
     */
    public static function get($timezone = null): DateTimeZone
    {
        if (!$timezone) {
            return self::getUtc();
        }
        if ($timezone instanceof DateTimeZone) {
            return $timezone;
        }
        Assert::string($timezone);
        if (isset(self::$timezoneMap[$timezone])) {
            return self::$timezoneMap[$timezone];
        }
        return self::$timezoneMap[$timezone] = new DateTimeZone($timezone);
    }

    /**
     * @param DateTimeImmutable        $dateTime
     * @param DateTimeZone|string|null $timezone
     * @return DateTimeImmutable
     */
    public static function ensureTimezone(DateTimeImmutable $dateTime, $timezone): DateTimeImmutable
    {
        $timezone = self::get($timezone);
        if ($timezone->getName() === $dateTime->getTimezone()->getName()) {
            return $dateTime;
        }
        return new DateTimeImmutable($dateTime->format('Y-m-d H:i:s.u'), $timezone);
    }

    /**
     * @param DateTimeImmutable $dateTime
     * @return DateTimeImmutable
     */
    public static function ensureUtc(DateTimeImmutable $dateTime): DateTimeImmutable
    {
        return self::ensureTimezone($dateTime, null);
    }

    /**
     * @param DateTimeImmutable        $dateTime
     * @param DateTimeZone|string|null $timezone
     * @return DateTimeImmutable
     */
    public static function convertToTimezone(DateTimeImmutable $dateTime, $timezone = null): DateTimeImmutable
    {
        $timezone = self::get($timezone);
        if ($timezone->getName() === $dateTime->getTimezone()->getName()) {
            return $dateTime;
        }
        return $dateTime->setTimezone($timezone);
    }

    /**
     * @param DateTimeImmutable $dateTime
     * @return DateTimeImmutable
     */
    public static function convertToUtc(DateTimeImmutable $dateTime): DateTimeImmutable
    {
        return self::convertToTimezone($dateTime, null);
    }

    /**
     * @param DateTimeZone $timezone
     * @return string
     */
    public static function getName(DateTimeZone $timezone): string
    {
        $parts = explode('/', $timezone->getName());
        if (count($parts) > 2) {
            $region = $parts[0];
            $name   = $parts[1] . ' - ' . $parts[2];
        } elseif (count($parts) > 1) {
            [$region, $name] = $parts;
        } else {
            $region = 'Other';
            $name   = $parts[0];
        }
        return $region . '/' . str_replace('_', ' ', $name);
    }

    /**
     */
    private function __construct()
    {
        // deliberately left empty - static class
    }
}
