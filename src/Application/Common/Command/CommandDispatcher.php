<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 14:05
 */

namespace App\Application\Common\Command;

use BadMethodCallException;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Trait CommandDispatcher
 *
 * @package App\Application\Common\Command
 */
trait CommandDispatcher
{
    /**
     * @var MessageBusInterface
     */
    private $commandBus;

    /**
     * @param MessageBusInterface $commandBus
     * @internal
     */
    public function setCommandBus(MessageBusInterface $commandBus): void
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param object[] $commands
     */
    protected function dispatchCommands(iterable $commands): void
    {
        foreach ($commands as $command) {
            $this->dispatchCommand($command);
        }
    }

    /**
     * @param object $command
     */
    protected function dispatchCommand(object $command): void
    {
        if (!$this->commandBus) {
            throw new BadMethodCallException('No command bus available.');
        }
        $this->commandBus->dispatch($command);
    }
}
