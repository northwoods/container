<?php

namespace Northwoods\Container\Zend;

use Eloquent\Phony\Phpunit\Phony;
use Northwoods\Container\InjectorConfig;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ContainerFactoryTest extends TestCase
{
    public function testFactoryCreatesContainer()
    {
        $factory = new ContainerFactory();
        $config = Phony::mock(InjectorConfig::class);

        $container = $factory($config->get());

        $this->assertInstanceOf(ContainerInterface::class, $container);
    }

    public function testFactoryConfiguresContainer()
    {
        $factory = new ContainerFactory();
        $config = Phony::mock(InjectorConfig::class);

        $container = $factory($config->get());

        $config->apply->times(1)->called();
    }
}
