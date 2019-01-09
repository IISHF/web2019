<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-09
 * Time: 09:37
 */

namespace App\Domain\Model\Common;

/**
 * Interface TagProvider
 *
 * @package App\Domain\Model\Common
 */
interface TagProvider
{
    /**
     * @return string[]
     */
    public function findAvailableTags(): array;
}
