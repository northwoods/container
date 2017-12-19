<?php
declare(strict_types=1);

namespace Northwoods\Container;

use Auryn\Injector;

interface InjectorConfig
{
    /**
     * Apply configuration to an injector.
     */
    public function apply(Injector $injector): void;
}
