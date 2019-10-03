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
 * Class CompositeCommandHandler
 *
 * @package App\Application\Common\Command
 */
class CompositeCommandHandler
{
    /**
     * @var MessageBusInterface
     */
    private $commandBus;

    /**
     * @param MessageBusInterface $commandBus
     */
    public function __construct(MessageBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param CompositeCommand $command
     */
    public function __invoke(CompositeCommand $command): void
    {
        foreach ($command as $c) {
            $this->commandBus->dispatch($c);
        }
    }
}
