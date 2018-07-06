<?php

namespace Northwoods\Container\Fixture;

use Auryn\Injector;
use Northwoods\Container\InjectorConfig;

class LazyConfigB implements InjectorConfig
{
    /** @var LazyClass */
    private $lazy;

    public function __construct(LazyClass $lazy)
    {
        $this->lazy = $lazy;
    }

    public function apply(Injector $injector): void
    {
    }
}
