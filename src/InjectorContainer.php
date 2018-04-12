<?php
declare(strict_types=1);

namespace Northwoods\Container;

use Auryn\Injector;
use Auryn\InjectorException;
use Psr\Container\ContainerInterface;

class InjectorContainer implements ContainerInterface
{
    const I_ALL = 31;

    /** @var Injector */
    private $injector;

    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
    }

    // ContainerInterface
    public function get($id)
    {
        if ($this->has($id) === false) {
            throw NotFoundException::classDoesNotExist($id);
        }

        try {
            return $this->injector->make($id);
        } catch (InjectorException $e) {
            throw ContainerException::couldNotMake($id, $e);
        }
    }

    // ContainerInterface
    public function has($id)
    {
        return is_string($id) && (class_exists($id) || $this->hasReference($id));
    }

    private function hasReference(string $id): bool
    {
        // https://github.com/rdlowrey/auryn/issues/157
        $details = $this->injector->inspect($id, self::I_ALL);
        return (bool) array_filter($details);
    }
}
