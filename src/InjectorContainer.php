<?php

namespace Northwoods\Container;

use Auryn\Injector;
use Auryn\InjectorException;
use Psr\Container\ContainerInterface;

class InjectorContainer implements ContainerInterface
{
    const I_ALL = 31;

    /**
     * @var Injector
     */
    private $injector;

    public function __construct(Injector $injector)
    {
        $this->injector = $injector;
    }

    // ContainerInterface
    public function get($id)
    {
        if (false === $this->has($id)) {
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
        return class_exists($id) || $this->hasReference($id);
    }

    /**
     * Check the injector has a reference
     *
     * @param string $id
     *
     * @return bool
     */
    private function hasReference($id)
    {
        // https://github.com/rdlowrey/auryn/issues/157
        $details = $this->injector->inspect($id, self::I_ALL);
        return (bool) array_filter($details);
    }
}
