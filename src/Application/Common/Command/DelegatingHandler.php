<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-03
 * Time: 14:05
 */

namespace App\Application\Common\Command;

use Symfony\Component\Messenger\MessageBusInterface;

/**
 * Trait DelegatingHandler
 *
 * @package App\Application\Common\Command
 */
trait DelegatingHandler
{
    /**
     * @var MessageBusInterface
     */
    private $commandBus;

    /**
     * @param object[] $commands
     */
    protected function dispatchCommands(iterable $commands): void
    {
        foreach ($commands as $command) {
            $this->commandBus->dispatch($command);
        }
    }
}
