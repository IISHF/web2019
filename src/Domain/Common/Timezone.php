<?php
/**
 * Timezone
 */

namespace App\Domain\Common;

use DateTimeZone;

/**
 * Class Timezone
 *
 * @package App\Domain\Common
 */
class Timezone
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
     * @param string $timezone
     * @return DateTimeZone
     */
    public static function get(string $timezone): DateTimeZone
    {
        if (isset(self::$timezoneMap[$timezone])) {
            return self::$timezoneMap[$timezone];
        }
        return self::$timezoneMap[$timezone] = new DateTimeZone($timezone);
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
