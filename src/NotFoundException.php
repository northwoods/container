<?php

namespace Northwoods\Container;

use InvalidArgumentException;
use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends InvalidArgumentException implements
    NotFoundExceptionInterface
{
    /**
     * @param string $class
     *
     * @return static
     */
    public static function classDoesNotExist($class)
    {
        return new static("Class $class does not exist");
    }
}
