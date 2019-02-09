<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 08:22
 */

namespace App\Domain\Model\Committee;

use Webmozart\Assert\Assert;

/**
 * Class TermType
 *
 * @package App\Domain\Model\Committee
 */
final class TermType
{
    public const ELECTED         = 1;
    public const NOMINATED_IISHF = 2;
    public const NOMINATED_NGB   = 3;

    /**
     * @var array
     */
    private static $availableTermTypes = [
        self::ELECTED         => 'Elected by AGM',
        self::NOMINATED_IISHF => 'Nominated by IISHF',
        self::NOMINATED_NGB   => 'Nominated by NGB',
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
    public static function getTermTypes(): array
    {
        return self::$availableTermTypes;
    }

    /**
     * @param int         $termType
     * @param string|null $default
     * @return string|null
     */
    public static function getTermTypeName(int $termType, ?string $default = null): ?string
    {
        return self::$availableTermTypes[$termType] ?? $default;
    }

    /**
     * @param int $termType
     * @return bool
     */
    public static function isValidTermType(int $termType): bool
    {
        return isset(self::$availableTermTypes[$termType]);
    }

    /**
     * @param int $termType
     */
    public static function assertValidTermType(int $termType): void
    {
        Assert::oneOf($termType, array_keys(self::$availableTermTypes));
    }
}
