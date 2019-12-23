<?php
/**
 * Copyright (c) 2019 Stefan Gehrig (stefan.gehrig.hn@googlemail.com).
 */

namespace App\DependencyInjection\Compiler;

use App\Test\DBAL\StaticConnectionFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class DBALTestCompilerPass
 *
 * @package App\DependencyInjection\Compiler
 * @see     https://github.com/dmaicher/doctrine-test-bundle
 */
class DBALTestCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritDoc}
     */
    public function process(ContainerBuilder $container): void
    {
        $factoryDef = new Definition(StaticConnectionFactory::class);
        $factoryDef->setDecoratedService('doctrine.dbal.connection_factory')
                   ->addArgument(new Reference('app.test.doctrine.dbal.connection_factory.inner'));
        $container->setDefinition('app.test.doctrine.dbal.connection_factory', $factoryDef);
    }
}
