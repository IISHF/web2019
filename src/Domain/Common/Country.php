<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 09:31
 */

namespace App\Domain\Common;

use Symfony\Component\Intl\Intl;
use Webmozart\Assert\Assert;

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
    private static $countries;

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
        $countries = self::getCountries();

        if (isset($countries[$code])) {
            return $countries[$code];
        }

        if ($defaultCode !== null) {
            return self::getCountryNameByCode($defaultCode, null);
        }

        return 'unknown';
    }

    /**
     * @return array
     */
    public static function getCountries(): array
    {
        if (!self::$countries) {
            self::$countries = Intl::getRegionBundle()
                                   ->getCountryNames();
        }
        return self::$countries;
    }

    /**
     * @param string $country
     * @return bool
     */
    public static function isValidCountry(string $country): bool
    {
        return isset(self::getCountries()[$country]);
    }

    /**
     * @param string $country
     */
    public static function assertValidCountry(string $country): void
    {
        Assert::oneOf($country, array_keys(self::getCountries()));
    }
}
