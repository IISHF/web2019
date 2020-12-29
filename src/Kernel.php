<?php

namespace App;

use App\Application\Common\Command;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        if (getenv('IS_VIRTUAL_ENV') === '1') {
            return '/dev/shm/iishf/cache/' . $this->getEnvironment();
        }
        return $this->getProjectDir() . '/var/cache/' . $this->environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        if (getenv('IS_VIRTUAL_ENV') === '1') {
            return '/dev/shm/iishf/log';
        }
        return $this->getProjectDir() . '/var/log';
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->import('../config/{packages}/*.yaml');
        $container->import('../config/{packages}/' . $this->environment . '/*.yaml');

        if (is_file(\dirname(__DIR__) . '/config/services.yaml')) {
            $container->import('../config/services.yaml');
            $container->import('../config/{services}_' . $this->environment . '.yaml');
        } elseif (is_file($path = \dirname(__DIR__) . '/config/services.php')) {
            (require $path)($container->withPath($path), $this);
        }
    }

    protected function configureRoutes(RoutingConfigurator $routes): void
    {
        $routes->import('../config/{routes}/' . $this->environment . '/*.yaml');
        $routes->import('../config/{routes}/*.yaml');

        if (is_file(\dirname(__DIR__) . '/config/routes.yaml')) {
            $routes->import('../config/routes.yaml');
        } elseif (is_file($path = \dirname(__DIR__) . '/config/routes.php')) {
            (require $path)($routes->withPath($path), $this);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->registerForAutoconfiguration(Command\CommandDispatchingHandler::class)
                  ->addTag('app.command_handler.command_dispatching');
        $container->registerForAutoconfiguration(Command\EventEmittingHandler::class)
                  ->addTag('app.command_handler.event_emitting');

        $container->addCompilerPass(new DependencyInjection\Compiler\MessageHandlerPass());

        if ($this->getEnvironment() === 'test') {
            $container->addCompilerPass(new DependencyInjection\Compiler\DBALTestCompilerPass());
        }
    }
}
