<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 09:53
 */

namespace App\Application\Common\Command;

/**
 * Interface EventRecorderInterface
 *
 * @package App\Application\Common\Command
 */
interface EventRecorderInterface
{
    /**
     * @return object[]
     */
    public function getEvents(): iterable;

    /**
     * @return void
     */
    public function clearEvents(): void;
}
