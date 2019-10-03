<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 09:13
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
