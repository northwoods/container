<?php
declare(strict_types=1);

namespace Northwoods\Container\Zend;

use Auryn\Injector;
use Northwoods\Container\InjectorConfig;
use Northwoods\Container\InjectorContainer;
use Psr\Container\ContainerInterface;

class ContainerFactory
{
    /** @var Injector */
    private $injector;

    public function __construct(Injector $injector = null)
    {
        $this->injector = $injector ?? new Injector();
    }

    public function __invoke(InjectorConfig $config): ContainerInterface
    {
        $container = new InjectorContainer($this->injector);

        $config->apply($this->injector);

        $this->injector->share($container);
        $this->injector->alias(ContainerInterface::class, InjectorContainer::class);

        return $container;
    }
}
