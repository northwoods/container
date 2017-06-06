<?php

namespace Northwoods\Container\Config;

use Auryn\Injector;
use Northwoods\Container\InjectorContainer;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ContainerConfigTest extends TestCase
{
    public function testConfig()
    {
        $injector = new Injector();

        $config = new ContainerConfig();
        $config->apply($injector);

        $container = $injector->make(ContainerInterface::class);

        $this->assertInstanceOf(InjectorContainer::class, $container);
    }
}
