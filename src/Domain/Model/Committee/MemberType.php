<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 08:26
 */

namespace App\Domain\Model\Committee;

use Webmozart\Assert\Assert;

/**
 * Class MemberType
 *
 * @package App\Domain\Model\Committee
 */
final class MemberType
{
    public const CHAIRMAN      = 1;
    public const VICE_CHAIRMAN = 2;
    public const MEMBER        = 3;

    /**
     * @var array
     */
    private static $availableMemberTypes = [
        self::CHAIRMAN      => 'Chairman',
        self::VICE_CHAIRMAN => 'Vice Chairman',
        self::MEMBER        => 'Member',
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
    public static function getMemberTypes(): array
    {
        return self::$availableMemberTypes;
    }

    /**
     * @param int         $memberType
     * @param string|null $default
     * @return string|null
     */
    public static function getMemberTypeName(int $memberType, ?string $default = null): ?string
    {
        return self::$availableMemberTypes[$memberType] ?? $default;
    }

    /**
     * @param int $memberType
     * @return bool
     */
    public static function isValidMemberType(int $memberType): bool
    {
        return isset(self::$availableMemberTypes[$memberType]);
    }

    /**
     * @param int $memberType
     */
    public static function assertValidMemberType(int $memberType): void
    {
        Assert::oneOf($memberType, array_keys(self::$availableMemberTypes));
    }
}
