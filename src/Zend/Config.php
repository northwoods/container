<?php
declare(strict_types=1);

namespace Northwoods\Container\Zend;

use ArrayObject;
use Auryn\Injector;
use Northwoods\Container\ContainerException;
use Northwoods\Container\InjectorConfig;
use Psr\Container\ContainerInterface;

class Config implements InjectorConfig
{
    /** @var array */
    private $config;

    /** @var bool */
    private $sharedByDefault = true;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Configure the injector using Zend Service Manager format
     */
    public function apply(Injector $injector): void
    {
        $dependencies = $this->config['dependencies'] ?? [];

        // Define the "config" service, accounting for the fact that Auryn
        // requires all returns are objects.
        $dependencies['services']['config'] = new ArrayObject($this->config, ArrayObject::ARRAY_AS_PROPS);

        $this->injectServices($injector, $dependencies);
        $this->injectFactories($injector, $dependencies);
        $this->injectInvokables($injector, $dependencies);
        $this->injectAliases($injector, $dependencies);
    }

    private function injectAliases(Injector $injector, array $dependencies): void
    {
        $aliases = $dependencies['aliases'] ?? [];
        foreach ($aliases as $alias => $target) {
            // Standard Auryn aliases do not work when chained. Work around by
            // lazily fetching the shared target from the container.
            $injector->share($alias)->share($target)->delegate($alias, $this->makeLazy($target));
        }
    }

    private function injectFactories(Injector $injector, array $dependencies): void
    {
        $factories = $dependencies['factories'] ?? [];
        foreach ($factories as $name => $factory) {
            $delegate = function () use ($injector, $name, $factory) {
                $container = $injector->make(ContainerInterface::class);
                $factory = $this->makeFactory($name, $factory);
                return $factory($container, $name);
            };
            if (isset($dependencies['delegators'][$name])) {
                $delegate = $this->makeDelegator(
                    $injector,
                    $name,
                    $delegate,
                    $dependencies['delegators'][$name]
                );
            }
            $injector->share($name)->delegate($name, $delegate);
        }
    }

    private function injectInvokables(Injector $injector, array $dependencies): void
    {
        $invokables = $dependencies['invokables'] ?? [];
        foreach ($invokables as $alias => $invokable) {
            if (is_string($alias) && $alias !== $invokable) {
                $injector->alias($alias, $invokable);
            }
            $delegate = function () use ($invokable) {
                return $this->makeFactory($invokable, $invokable);
            };
            if (isset($dependencies['delegators'][$invokable])) {
                $delegate = $this->makeDelegator(
                    $injector,
                    $invokable,
                    $delegate,
                    $dependencies['delegators'][$invokable]
                );
            }
            $injector->share($invokable)->delegate($invokable, $delegate);
        }
    }

    private function injectServices(Injector $injector, array $dependencies): void
    {
        $services = $dependencies['services'] ?? [];
        foreach ($services as $name => $service) {
            $injector->share($name)->delegate($name, $this->makeIdentity($service));
        }
    }

    private function makeDelegator(Injector $injector, string $name, callable $callback, array $delegators): callable
    {
        return function () use ($injector, $name, $callback, $delegators) {
            foreach ($delegators as $delegator) {
                $container = $injector->make(ContainerInterface::class);
                $delegator = $this->makeFactory($name, $delegator);
                $instance = $delegator($container, $name, $callback);
                $callback = $this->makeIdentity($instance);
            }
            return $instance ?? $callback();
        };
    }

    /**
     * @param string|callable $factory
     */
    private function makeFactory(string $name, $factory): callable
    {
        if (is_callable($factory)) {
            return $factory;
        }

        if (is_string($factory) && !class_exists($factory)) {
            throw ContainerException::expectedInvokable($name);
        }

        $factory = new $factory();

        if (is_callable($factory)) {
            return $factory;
        }

        throw ContainerException::expectedInvokable($name);
    }

    private function makeIdentity($object): callable
    {
        return static function () use ($object) {
            return $object;
        };
    }

    private function makeLazy(string $name): callable
    {
        return static function (ContainerInterface $container) use ($name) {
            return $container->get($name);
        };
    }
}
