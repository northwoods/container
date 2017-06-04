<?php

namespace Northwoods\Container;

use Auryn\InjectorException;
use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

class ContainerException extends RuntimeException implements
    ContainerExceptionInterface
{
    /**
     * @param string $class
     * @param InjectorException $previous
     *
     * @return static
     */
    public static function couldNotMake($class, InjectorException $e)
    {
        return new static("Could not make $class", 0, $e);
    }
}
