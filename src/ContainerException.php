<?php
declare(strict_types=1);

namespace Northwoods\Container;

use Auryn\InjectorException;
use Psr\Container\ContainerExceptionInterface;

class ContainerException extends \RuntimeException implements
    ContainerExceptionInterface
{
    const COULD_NOT_MAKE = 1;
    const EXPECTED_INVOKABLE = 2;

    public static function couldNotMake(string $class, InjectorException $previous): ContainerException
    {
        return new static("Could not make '$class'", self::COULD_NOT_MAKE, $previous);
    }

    public static function expectedInvokable(string $class): ContainerException
    {
        return new static("Expected '$class' to be invokable", self::EXPECTED_INVOKABLE);
    }
}
