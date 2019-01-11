<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-11
 * Time: 10:53
 */

namespace App\Utils;

/**
 * Class Number
 *
 * @package App\Utils
 */
final class Number
{
    /**
     */
    private function __construct()
    {
    }

    /**
     * @param int  $bytes
     * @param bool $si
     * @return array
     */
    public static function fileSize(int $bytes, bool $si = true): array
    {
        $unit = $si ? 1000 : 1024;
        if ($bytes <= $unit) {
            return [$bytes, 'B'];
        }
        $exp = (int)(log($bytes) / log($unit));
        $pre = ($si ? 'kMGTPE' : 'KMGTPE');
        $pre = $pre[$exp - 1] . ($si ? '' : 'i');

        return [$bytes / ($unit ** $exp), $pre . 'B'];
    }
}
