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
class MemberType
{
    public const MEMBER_TYPE_CHAIRMAN      = 1;
    public const MEMBER_TYPE_VICE_CHAIRMAN = 2;
    public const MEMBER_TYPE_MEMBER        = 3;

    /**
     * @var array
     */
    private static $availableMemberTypes = [
        self::MEMBER_TYPE_CHAIRMAN      => 'Chairman',
        self::MEMBER_TYPE_VICE_CHAIRMAN => 'Vice Chairman',
        self::MEMBER_TYPE_MEMBER        => 'Member',
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
