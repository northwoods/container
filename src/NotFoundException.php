<?php
declare(strict_types=1);

namespace Northwoods\Container;

use Psr\Container\NotFoundExceptionInterface;

class NotFoundException extends \InvalidArgumentException implements
    NotFoundExceptionInterface
{
    public static function classDoesNotExist(string $class): NotFoundException
    {
        return new static("Class $class does not exist");
    }
}
