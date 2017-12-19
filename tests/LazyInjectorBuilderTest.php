<?php

namespace Northwoods\Container;

use Auryn\Injector;
use Eloquent\Phony\Phpunit\Phony;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

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
}
