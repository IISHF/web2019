<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2018-11-28
 * Time: 09:17
 */

namespace App\Domain\Common;

use App\Utils\Text;
use Gedmo\Sluggable\Util\Urlizer as GedmoUrlizer;

/**
 * Class Urlizer
 *
 * @package App\Domain\Common
 */
final class Urlizer
{
    /**
     */
    private function __construct()
    {
        // static class - left empty
    }

    /**
     * @param string $text
     * @param string $separator
     * @return string
     */
    public static function urlize(string $text, string $separator = '-'): string
    {
        return GedmoUrlizer::urlize($text, $separator);
    }

    /**
     * @param string   $text
     * @param callable $uniqueTest
     * @param int      $maxLength
     * @param string   $separator
     * @return string
     */
    public static function urlizeUnique(
        string $text,
        callable $uniqueTest,
        int $maxLength = 128,
        string $separator = '-'
    ): string {
        $baseSlug = self::urlize($text, $separator);
        $slug     = Text::shorten($baseSlug, $maxLength, '-');
        $i        = 1;
        while ($uniqueTest($slug)) {
            $slug = Text::shorten($baseSlug . '-' . $i, $maxLength, '-');
            $i++;
        }
        return $slug;
    }
}
