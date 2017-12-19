<?php
declare(strict_types=1);

namespace Northwoods\Container\Config;

use Auryn\Injector;
use Northwoods\Container\InjectorConfig;
use Psr\Container\ContainerInterface;

class ServiceConfig implements InjectorConfig
{
    /** @var array */
    private $config;

    /** @var bool */
    private $sharedByDefault = true;

    /** @var array */
    private $shared = [];

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    // InjectorConfig
    public function apply(Injector $injector): void
    {
        // Enable or disable sharing all services by default.
        if (isset($this->config['shared_by_default'])) {
            $this->sharedByDefault = (bool) $this->config['shared_by_default'];
        }

        // Overload specific services to be shared.
        if (isset($this->config['shared'])) {
            $this->shared = $this->config['shared'];
        }

        // Aliases are exactly the same as aliases. Natch.
        if (isset($this->config['aliases'])) {
            $this->applyAliases($injector, $this->config['aliases']);
        }

        // Delegators are effectively a chain of prepare() statements.
        if (isset($this->config['delegators'])) {
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

    private function isShared(string $name): bool
    {
        if (isset($this->shared[$name])) {
            return (bool) $this->shared[$name];
        }

        return $this->sharedByDefault;
    }

    private function applyAliases(Injector $injector, array $services): void
    {
        foreach ($services as $name => $object) {
            $injector->alias($name, $object);
            if ($this->isShared($name)) {
                $injector->share($name);
            }
        }
    }

    private function applyDelegates(Injector $injector, array $services): void
    {
        foreach ($services as $name => $object) {
            $injector->delegate($name, $object);
            if ($this->isShared($name)) {
                $injector->share($name);
            }
        }
    }

    private function applyDelegators(Injector $injector, array $delegators): void
    {
        // https://github.com/rdlowrey/auryn#prepares-and-setter-injection
        foreach ($delegators as $service => $prepares) {
            $injector->prepare($service, $this->createDelegator($service, $prepares));
            if ($this->isShared($service)) {
                $injector->share($service);
            }
        }
    }

    /**
     * Create a chained prepare function.
     */
    private function createDelegator(string $service, array $delegators): callable
    {
        // Prepare the service by calling each delegator with the result of the previous.
        return function ($instance, Injector $injector) use ($service, $delegators) {
            return array_reduce($delegators, $this->delegatorReducer($injector, $service), $instance);
        };
    }

    /**
     * Create a reducer for a chained prepare function.
     */
    private function delegatorReducer(Injector $injector, string $service): callable
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
     * Curry the delegator to only require a container.
     */
    private function curryDelegator(callable $delegator, string $service, callable $callable): callable
    {
        return static function (ContainerInterface $container) use ($delegator, $service, $callable) {
            return $delegator($container, $service, $callable);
        };
    }

    /**
     * Create a k combinator for a value.
     */
    private function k($x): callable
    {
        return static function () use ($x) {
            return $x;
        };
    }

    /**
     * Create k combinators for multiple values.
     */
    private function kAll(array $values): array
    {
        return array_map(
            function ($x): callable {
                return $this->k($x);
            },
            $values
        );
    }
}
