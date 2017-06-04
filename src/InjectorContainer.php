<?php

namespace Northwoods\Container;

use Auryn\Injector;
use Auryn\InjectorException;
use Psr\Container\ContainerInterface;

class InjectorContainer implements ContainerInterface
{
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
        return class_exists($id) || $this->hasAlias($id);
    }

    /**
     * Check the injector has an alias
     *
     * @param string $id
     *
     * @return bool
     */
    private function hasAlias($id)
    {
        $type = Injector::I_ALIASES;
        $details = $this->injector->inspect($id, $type);

        return !empty($details[$type][$id]);
    }
}
