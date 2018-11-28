<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 09:31
 */

namespace App\Domain\Common;

use Symfony\Component\Intl\Intl;

/**
 * Class Country
 *
 * @package App\Domain\Common
 */
final class Country
{
    /**
     * @var array
     */
    private static $countryNames;

    /**
     */
    private function __construct()
    {
        // static class - left empty
    }

    /**
     * @param string      $code
     * @param string|null $defaultCode
     * @return string
     */
    public static function getCountryNameByCode(string $code, ?string $defaultCode = 'DE'): string
    {
        if (!self::$countryNames) {
            self::$countryNames = Intl::getRegionBundle()
                                      ->getCountryNames();
        }

        if (isset(self::$countryNames[$code])) {
            return self::$countryNames[$code];
        }

        if ($defaultCode !== null) {
            return self::getCountryNameByCode($defaultCode, null);
        }

        return 'unknown';
    }
}
