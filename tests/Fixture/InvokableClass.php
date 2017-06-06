<?php

namespace Northwoods\Container\Fixture;

class InvokableClass
{
    public function __invoke()
    {
        return true;
    }
}
