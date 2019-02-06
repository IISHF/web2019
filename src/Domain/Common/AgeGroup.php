<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-05
 * Time: 07:31
 */

namespace App\Domain\Common;

use Webmozart\Assert\Assert;

/**
 * Class AgeGroup
 *
 * @package App\Domain\Common
 */
final class AgeGroup
{
    public const AGE_GROUP_VETERANS = 'veterans';
    public const AGE_GROUP_MEN      = 'men';
    public const AGE_GROUP_WOMEN    = 'women';
    public const AGE_GROUP_U19      = 'u19';
    public const AGE_GROUP_U16      = 'u16';
    public const AGE_GROUP_U13      = 'u13';

    /**
     * @var array
     */
    private static $availableAgeGroups = [
        self::AGE_GROUP_VETERANS => 'Veterans',
        self::AGE_GROUP_MEN      => 'Men',
        self::AGE_GROUP_WOMEN    => 'Women',
        self::AGE_GROUP_U19      => 'U19 Junior',
        self::AGE_GROUP_U16      => 'U16 Youth',
        self::AGE_GROUP_U13      => 'U13 Pee-Wee',
    ];

    /**
     */
    private function __construct()
    {
        // static class - left empty
    }

    /**
     * @return array
     */
    public static function getAgeGroups(): array
    {
        return self::$availableAgeGroups;
    }

    /**
     * @param string      $ageGroup
     * @param string|null $default
     * @return string|null
     */
    public static function getAgeGroupName(string $ageGroup, ?string $default = null): ?string
    {
        return self::$availableAgeGroups[$ageGroup] ?? $default;
    }

    /**
     * @param string $ageGroup
     * @return bool
     */
    public static function isValidAgeGroup(string $ageGroup): bool
    {
        return isset(self::$availableAgeGroups[$ageGroup]);
    }

    /**
     * @param string $ageGroup
     */
    public static function assertValidAgeGroup(string $ageGroup): void
    {
        Assert::oneOf($ageGroup, array_keys(self::$availableAgeGroups));
    }
}
