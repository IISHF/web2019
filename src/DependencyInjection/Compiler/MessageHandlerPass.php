<?php
/**
 * Created by PhpStorm.
 * User: stefan
 * Date: 2019-01-04
 * Time: 09:13
 */

namespace App\DependencyInjection\Compiler;

use App\Application\Common\Command\RecordsEvents;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Compiler\PriorityTaggedServiceTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class MessageHandlerPass
 *
 * @package App\DependencyInjection\Compiler
 */
class MessageHandlerPass implements CompilerPassInterface
{
    use PriorityTaggedServiceTrait;

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container): void
    {
        if ($container->has('messenger.bus.commands')) {
            $commandDispatchingHandlers = $this->findAndSortTaggedServices(
                'app.command_handler.command_dispatching',
                $container
            );
            foreach ($commandDispatchingHandlers as $commandDispatchingHandlerReference) {
                $commandDispatchingHandler = $container->getDefinition($commandDispatchingHandlerReference);
                $commandDispatchingHandler->addMethodCall('setCommandBus', [new Reference('messenger.bus.commands')]);
            }
        }

        if ($container->has(RecordsEvents::class)) {
            $eventEmittingHandlers = $this->findAndSortTaggedServices(
                'app.command_handler.event_emitting',
                $container
            );
            foreach ($eventEmittingHandlers as $eventEmittingHandlerReference) {
                $eventEmittingHandler = $container->getDefinition($eventEmittingHandlerReference);
                $eventEmittingHandler->addMethodCall('setEventRecorder', [new Reference(RecordsEvents::class)]);
            }
        }
    }
}
