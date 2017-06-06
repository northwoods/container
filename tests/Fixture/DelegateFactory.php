<?php

namespace Northwoods\Container\Fixture;

class DelegateFactory
{
    public function __invoke()
    {
        return new DelegatedClass();
    }
}
