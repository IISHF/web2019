<?php
/**
 * CompositeCommandHandler
 *
 * @author    Stefan Gehrig <gehrig@teqneers.de>
 * @package   App\Application\Common\Command
 * @copyright Copyright (C) 2019 TEQneers GmbH & Co. KG. All rights reserved.
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
