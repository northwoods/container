<?php

namespace Northwoods\Container\Fixture;

class PreparedPrep
{
    public function __invoke($container, $service, $callable)
    {
        $instance = $callable();
        $instance->prepared = true;
        return $instance;
    }
}
