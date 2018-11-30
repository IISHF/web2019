<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-30
 * Time: 12:42
 */

namespace App\Utils;

/**
 * Class Text
 *
 * @package App\Utils
 */
final class Text
{
    /**
     * TextUtils constructor.
     */
    private function __construct()
    {
    }

    /**
     * @param string $text
     * @param int    $characters
     * @param string $suffix
     * @param string $encoding
     * @return string
     */
    public static function excerpt(
        string $text,
        int $characters = 200,
        string $suffix = '…',
        string $encoding = 'UTF-8'
    ): string {
        $text    = str_replace('  ', ' ', preg_replace('/\r?\n/', ' ', $text));
        $textLen = mb_strlen($text, $encoding);

        if ($textLen <= $characters) {
            return $text;
        }

        $characters -= mb_strlen($suffix, $encoding);
        return mb_substr($text, 0, $characters, $encoding) . $suffix;
    }

    /**
     * @param string $string
     * @param int    $maxLength
     * @param string $ellipsis
     * @param string $encoding
     * @return string
     */
    public static function shorten(
        string $string,
        int $maxLength,
        string $ellipsis = '…',
        string $encoding = 'UTF-8'
    ): string {
        $strLen = mb_strlen($string, $encoding);
        if ($strLen <= $maxLength) {
            return $string;
        }

        $length1 = (int)floor($maxLength / 2);
        $length2 = $maxLength - $length1 - mb_strlen($ellipsis, $encoding);
        return mb_substr($string, 0, $length1, $encoding) . $ellipsis . mb_substr($string, -1 * $length2);
    }
}
