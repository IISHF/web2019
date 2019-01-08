<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 13:42
 */

namespace App\Application\Common\Command;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Class DelegatingMessageHandler
 *
 * @package App\Application\Common\Command
 */
class DelegatingMessageHandler implements MessageHandlerInterface
{
    use CommandDispatcher;

    /**
     * @param MessageBusInterface $commandBus
     */
    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }
}
