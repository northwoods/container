<?php

namespace Northwoods\Container\Config;

use Auryn\Injector;
use Northwoods\Container\InjectorConfig;
use Northwoods\Container\InjectorContainer;
use Psr\Container\ContainerInterface;

class ContainerConfig implements InjectorConfig
{
    public function apply(Injector $injector)
    {
        // Optional: Declare a single container instance.
        $injector->share(ContainerInterface::class);

        // Use InjectorContainer as the implementation of ContainerInterface.
        $injector->alias(ContainerInterface::class, InjectorContainer::class);

        // InjectorContainer will wrap this Injector instance.
        $injector->define(InjectorContainer::class, [':injector' => $injector]);
    }
}
