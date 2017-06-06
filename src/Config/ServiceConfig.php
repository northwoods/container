<?php

namespace Northwoods\Container\Config;

use Auryn\Injector;
use Northwoods\Container\InjectorConfig;
use Psr\Container\ContainerInterface;

class ServiceConfig implements InjectorConfig
{
    /**
     * @var array
     */
    private $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @param Injector $injector
     * @return void
     */
    public function apply(Injector $injector)
    {
        // Aliases are exactly the same as aliases. Natch.
        if (isset($this->config['aliases'])) {
            $this->applyAliases($injector, $this->config['aliases']);
        }

        if (isset($this->config['delegators'])) {
            // Delegators are effectively a chain of prepare() statements.
            $this->applyDelegators($injector, $this->config['delegators']);
        }

        // Factories are exactly the same thing as a delegate.
        if (isset($this->config['factories'])) {
            $this->applyDelegates($injector, $this->config['factories']);
        }

        // Invokables are references to classes that have no constructor parameters.
        // This means nothing in Auryn so we just alias the reference.
        if (isset($this->config['invokables'])) {
            $this->applyAliases($injector, $this->config['invokables']);
        }

        // Services are already constructed instances of something. To handle this,
        // we simply wrap the instance in a callable that returns the instance.
        if (isset($this->config['services'])) {
            $this->applyDelegates($injector, $this->kAll($this->config['services']));
        }
    }

    /**
     * @param array $services
     * @return void
     */
    private function applyAliases(Injector $injector, array $services)
    {
        foreach ($services as $name => $object) {
            $injector->alias($name, $object);
        }
    }

    /**
     * @param array $services
     * @return void
     */
    private function applyDelegates(Injector $injector, array $services)
    {
        foreach ($services as $name => $object) {
            $injector->delegate($name, $object);
        }
    }

    /**
     * @param array $delegators
     * @return void
     */
    private function applyDelegators(Injector $injector, array $delegators)
    {
        // https://github.com/rdlowrey/auryn#prepares-and-setter-injection
        foreach ($delegators as $service => $prepares) {
            $injector->prepare($service, $this->createDelegator($service, $prepares));
        }
    }

    /**
     * Create a chained prepare()
     *
     * @param string $service
     * @param string[] $delegators
     * @return callable
     */
    private function createDelegator($service, array $delegators)
    {
        // Prepare the service by calling each delegator with the result of the previous.
        return function ($instance, $injector) use ($service, $delegators) {
            return array_reduce($delegators, $this->delegatorReducer($injector, $service), $instance);
        };
    }

    /**
     * Create a reducer for a chained prepare()
     *
     * @param string $service
     * @return callable
     */
    private function delegatorReducer(Injector $injector, $service)
    {
        // https://docs.zendframework.com/zend-expressive/features/container/delegator-factories/
        return function ($instance, $delegator) use ($injector, $service) {
            if (!is_callable($delegator)) {
                $delegator = $injector->make($delegator);
            }
            $callable = $this->k($instance);
            return $injector->execute($this->curryDelegator($delegator, $service, $callable));
        };
    }

    /**
     * Curry the delegator to only require a container
     *
     * @param callable $delegator that will be ultimately called
     * @param string $service name of service being prepared
     * @param callable $callable that returns the instance
     * @return callable
     */
    private function curryDelegator(callable $delegator, $service, callable $callable)
    {
        return static function (ContainerInterface $container) use ($delegator, $service, $callable) {
            return $delegator($container, $service, $callable);
        };
    }

    /**
     * Returns a function that always returns the same value
     *
     * Also known as a "kestrel" or "k combinator".
     *
     * @param mixed $x
     * @return callable
     */
    private function k($x)
    {
        return static function () use ($x) {
            return $x;
        };
    }

    /**
     * @param array $values
     * @return callable[]
     */
    private function kAll(array $values)
    {
        return array_map(
            function ($x) {
                return $this->k($x);
            },
            $values
        );
    }
}
