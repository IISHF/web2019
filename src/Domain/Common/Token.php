<?php
/**
 * Copyright (c) 2019 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\Domain\Common;

/**
 * Class Token
 *
 * @package App\Domain\Common
 */
final class Token
{
    /**
     */
    private function __construct()
    {
        // static class
    }

    /**
     * @param int  $length
     * @param bool $rawOutput
     * @return string
     */
    public static function random(int $length, bool $rawOutput = false): string
    {
        $string = random_bytes($length);
        return !$rawOutput ? bin2hex($string) : $string;
    }

    /**
     * @param string $string
     * @param bool   $rawInput
     * @param bool   $rawOutput
     * @return string
     */
    public static function hash(string $string, bool $rawInput = false, bool $rawOutput = false): string
    {
        if (!$rawInput) {
            $string = hex2bin($string);
        }
        return hash('sha256', $string, $rawOutput);
    }

    /**
     * @param string $hex
     * @return string
     */
    public static function binary(string $hex): string
    {
        return @hex2bin($hex);
    }

    /**
     * @param string $binary
     * @return string
     */
    public static function hex(string $binary): string
    {
        return bin2hex($binary);
    }
}
