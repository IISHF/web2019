<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-09
 * Time: 08:57
 */

namespace App\Utils;

/**
 * Class Tags
 *
 * @package App\Utils
 */
final class Tags
{
    /**
     */
    private function __construct()
    {
    }

    /**
     * @param array       $tagRows
     * @param string|null $column
     * @return string[]
     */
    public static function createTagList(array $tagRows, ?string $column = 'tags'): array
    {
        if ($column !== null) {
            $tagRows = array_column($tagRows, $column);
        }

        $list = array_keys(
            array_reduce(
                $tagRows,
                function (array $carry, array $tags) {
                    foreach ($tags as $tag) {
                        $carry[$tag] = true;
                    }
                    return $carry;
                },
                []
            )
        );

        $collator = new \Collator('en_GB');
        $collator->sort($list, \Collator::SORT_STRING);

        return $list;
    }

}
