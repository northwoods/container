<?php
declare(strict_types=1);

namespace Northwoods\Container\Zend;

use Auryn\Injector;
use Northwoods\Container\InjectorConfig;
use Northwoods\Container\InjectorContainer;
use Psr\Container\ContainerInterface;

class ContainerFactory
{
    public function __invoke(InjectorConfig $config): ContainerInterface
    {
        $injector = new Injector();
        $container = new InjectorContainer($injector);

        $config->apply($injector);

        $injector->share($container);
        $injector->alias(ContainerInterface::class, InjectorContainer::class);

        return $container;
    }
}
