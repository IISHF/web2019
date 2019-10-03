<?php
/**
 * CommandDispatchingHandler
 *
 * @author    Stefan Gehrig <gehrig@teqneers.de>
 * @package   App\Application\Common\Command
 * @copyright Copyright (C) 2019 TEQneers GmbH & Co. KG. All rights reserved.
 */

namespace App\Application\Common\Command;

use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Interface CommandDispatchingHandler
 *
 * @package App\Application\Common\Command
 */
interface CommandDispatchingHandler
{
    /**
     * @param MessageBusInterface $commandBus
     */
    public function setCommandBus(MessageBusInterface $commandBus): void;
}
