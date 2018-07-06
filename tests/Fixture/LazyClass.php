<?php

namespace Northwoods\Container\Fixture;

class LazyClass
{
    /** @var string */
    public $env;

    public function __construct(string $env)
    {
        $this->env = $env;
    }
}
