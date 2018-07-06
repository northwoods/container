<?php

namespace Northwoods\Container\Fixture;

use Auryn\Injector;
use Northwoods\Container\InjectorConfig;

class LazyConfigA implements InjectorConfig
{
    public function apply(Injector $injector): void
    {
        $injector->define(LazyClass::class, [':env' => 'test']);
    }
}
