<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-08
 * Time: 08:14
 */

namespace App\Application\Common\Command;

/**
 * Interface RecordsEvents
 *
 * @package App\Application\Common\Command
 */
interface RecordsEvents
{
    /**
     * @param object $event
     */
    public function recordEvent(object $event): void;
}
