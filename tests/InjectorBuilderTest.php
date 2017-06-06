<?php

namespace Northwoods\Container;

use Auryn\Injector;
use Eloquent\Phony\Phpunit\Phony;
use PHPUnit\Framework\TestCase;

class InjectorBuilderTest extends TestCase
{
    public function testCreatesInjector()
    {
        $builder = new InjectorBuilder([]);
        $injector = $builder->build();

        $this->assertInstanceOf(Injector::class, $injector);
    }

    public function testAppliesConfigs()
    {
        // Mock
        $a = Phony::mock(InjectorConfig::class);
        $b = Phony::mock(InjectorConfig::class);

        $configs = [
            $a->get(),
            $b->get(),
        ];

        // Execute
        $builder = new InjectorBuilder([$a->get(), $b->get()]);
        $injector = $builder->build();

        // Verify
        Phony::inOrder(
            $a->apply->calledWith($injector),
            $b->apply->calledWith($injector)
        );
    }
}
