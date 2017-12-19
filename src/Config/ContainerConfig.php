<?php
declare(strict_types=1);

namespace Northwoods\Container\Config;

use Auryn\Injector;
use Northwoods\Container\InjectorConfig;
use Northwoods\Container\InjectorContainer;
use Psr\Container\ContainerInterface;

class ContainerConfig implements InjectorConfig
{
    public function apply(Injector $injector): void
    {
        // Assume that the container will be shared
        $injector->share(ContainerInterface::class);

        // Use the injector as the preferred container implementation
        $injector->alias(ContainerInterface::class, InjectorContainer::class);

        // The container will wrap this injector
        $injector->define(InjectorContainer::class, [':injector' => $injector]);
    }
}
