<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 09:13
 */

namespace App\Application\Common\Command;

/**
 * Interface EventEmittingHandler
 *
 * @package App\Application\Common\Command
 */
interface EventEmittingHandler
{
    /**
     * @param RecordsEvents $eventRecorder
     */
    public function setEventRecorder(RecordsEvents $eventRecorder): void;
}
