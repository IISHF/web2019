<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-02-09
 * Time: 08:34
 */

namespace App\Domain\Model\Event\Game;

use Webmozart\Assert\Assert;

/**
 * Class GameType
 *
 * @package App\Domain\Model\Event\Game
 */
final class GameType
{
    public const GAME_TYPE_FIXED    = 1;
    public const GAME_TYPE_EDITABLE = 2;

    /**
     * @var array
     */
    private static $availableGameTypes = [
        self::GAME_TYPE_FIXED    => 'Fixed (Preliminary Game)',
        self::GAME_TYPE_EDITABLE => 'Editable (Final Game)',
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
    public static function getGameTypes(): array
    {
        return self::$availableGameTypes;
    }

    /**
     * @param int         $gameType
     * @param string|null $default
     * @return string|null
     */
    public static function getGameTypeName(int $gameType, ?string $default = null): ?string
    {
        return self::$availableGameTypes[$gameType] ?? $default;
    }

    /**
     * @param int $gameType
     * @return bool
     */
    public static function isValidGameType(int $gameType): bool
    {
        return isset(self::$availableGameTypes[$gameType]);
    }

    /**
     * @param int $gameType
     */
    public static function assertValidGameType(int $gameType): void
    {
        Assert::oneOf($gameType, array_keys(self::$availableGameTypes));
    }
}
