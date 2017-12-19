<?php
declare(strict_types=1);

namespace Northwoods\Container;

use Auryn\InjectorException;
use Psr\Container\ContainerExceptionInterface;

class ContainerException extends \RuntimeException implements
    ContainerExceptionInterface
{
    public static function couldNotMake(string $class, InjectorException $previous): ContainerException
    {
        return new static("Could not make $class", 0, $previous);
    }
}
