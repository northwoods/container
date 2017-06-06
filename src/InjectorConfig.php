<?php

namespace Northwoods\Container;

use Auryn\Injector;

interface InjectorConfig
{
    /**
     * Apply configuration to the injector
     *
     * @return void
     */
    public function apply(Injector $injector);
}
