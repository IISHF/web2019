<?php
/**
 * EventEmittingHandler
 *
 * @author    Stefan Gehrig <gehrig@teqneers.de>
 * @package   App\Application\Common\Command
 * @copyright Copyright (C) 2019 TEQneers GmbH & Co. KG. All rights reserved.
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
