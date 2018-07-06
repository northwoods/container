<?php

namespace Northwoods\Container;

use Auryn\Injector;
use Eloquent\Phony\Phpunit\Phony;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Northwoods\Container\Fixture;

class LazyInjectorBuilderTest extends TestCase
{
    public function testBuilder()
    {
        // Execute
        $builder = new LazyInjectorBuilder([
            Config\ContainerConfig::class,
        ]);

        $injector = $builder->build();

        // Verify
        $this->assertInstanceOf(Injector::class, $injector);
        $this->assertInstanceOf(ContainerInterface::class, $injector->make(ContainerInterface::class));
    }

    public function testBuilderOrder()
    {
        // Execute
        $builder = new LazyInjectorBuilder([
            Fixture\LazyConfigA::class,
            Fixture\LazyConfigB::class,
        ]);

        $injector = $builder->build();

        // Verify
        $this->assertInstanceOf(Injector::class, $injector);
    }
}
