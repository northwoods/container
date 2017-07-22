Northwoods Container
====================

[![Become a Supporter](https://img.shields.io/badge/patreon-sponsor%20me-e6461a.svg)](https://www.patreon.com/shadowhand)
[![Latest Stable Version](https://img.shields.io/packagist/v/northwoods/container.svg)](https://packagist.org/packages/northwoods/container)
[![License](https://img.shields.io/packagist/l/northwoods/container.svg)](https://github.com/northwoods/container/blob/master/LICENSE)
[![Build Status](https://travis-ci.org/northwoods/container.svg)](https://travis-ci.org/northwoods/container)
[![Code Coverage](https://scrutinizer-ci.com/g/northwoods/container/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/northwoods/container/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/northwoods/container/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/northwoods/container/?branch=master)

[Auryn][auryn] is awesome, so why not use it as a container when packages require it?

[auryn]: https://packagist.org/packages/rdlowrey/auryn

_**Note:** This goes completely against the philosophy of not using Auryn as a service locator.
This package is only meant to be a pragmatic solution for Auryn users that want to use a package
that requires a service locator._

Attempts to be [PSR-1][psr-1], [PSR-2][psr-2], [PSR-4][psr-4], and [PSR-11][psr-11] compliant.

[psr-1]: http://www.php-fig.org/psr/psr-1/
[psr-2]: http://www.php-fig.org/psr/psr-2/
[psr-4]: http://www.php-fig.org/psr/psr-4/
[psr-11]: http://www.php-fig.org/psr/psr-11/

## Install

```
composer require northwoods/container
```

## Usage

```php
use Auryn\Injector;
use Northwoods\Container\InjectorContainer;
use Psr\Container\ContainerInterface;

// Make an Injector and configure it.
$injector = new Injector();

// Optional: Declare a single container instance.
$injector->share(ContainerInterface::class);

// Use InjectorContainer as the implementation of ContainerInterface.
$injector->alias(ContainerInterface::class, InjectorContainer::class);

// InjectorContainer will wrap this Injector instance.
$injector->define(InjectorContainer::class, [':injector' => $injector]);
```

### Configuration

This package provides a `InjectorBuilder` that can be used to configure Auryn using separate classes.
The builder takes a list of configuration objects and applies each of them to the injector.

First, create an implementation of `InjectorConfig`:

```php
namespace Acme;

use Auryn\Injector;
use Northwoods\Container\InjectorConfig;
use Northwoods\Container\InjectorContainer;
use Psr\Container\ContainerInterface;

class ContainerConfig implements InjectorConfig
{
    public function apply(Injector $injector)
    {
        // Optional: Declare a single container instance.
        $injector->share(ContainerInterface::class);

        // Use InjectorContainer as the implementation of ContainerInterface.
        $injector->alias(ContainerInterface::class, InjectorContainer::class);

        // InjectorContainer will wrap this Injector instance.
        $injector->define(InjectorContainer::class, [':injector' => $injector]);
    }
}
```

_**Note: This exact configuration is available in `Northwoods\Container\Config\ContainerConfig`.**_

And then use it to create the injector:

```php
use Acme\ContainerConfig;
use Northwoods\Container\InjectorBuilder;

$builder = new InjectorBuilder([
    new ContainerConfig(),
]);

$injector = $builder->build();
$container = $injector->make(ContainerInterface::class);
```

_**Note: An instance of Auryn can also be provided by calling `build($injector)`.**_

### Service Definitions

This package also includes a `ServiceConfig` class that supports applying a "service definition" map
in the [Zend Service Manager][zend-service-manager] style:

```php
use Northwoods\Container\InjectorBuilder;
use Northwoods\Container\Config;

$builder = new InjectorBuilder([
    new Config\ContainerConfig(),
    new Config\ServiceConfig(require '/path/to/services.php'),
]);

$injector = $builder->build();
```

[zend-service-manager]: https://docs.zendframework.com/zend-servicemanager/configuring-the-service-manager/

The following definitions are supported by `ServiceConfig`:

- `aliases` will be aliased
- `delegators` will create a chained prepare
- `factories` will create a delegate
- `invokables` will be aliased
- `services` will be wrapped as a delegate
- `shared` will enable (or disable) sharing of specific classes
- `shared_by_default` will enable (or disable) sharing by default

### Identifiers

[PSR-11][psr-11] does not require the container identifier to be a class name, while [Auryn][auryn] does.
The only exception to this rule in Auryn is that a [class alias][auryn-class-alias] can be anything.
These container "service names" must resolve to a class and will need to be aliased.

[auryn-class-alias]: https://github.com/rdlowrey/auryn#type-hint-aliasing

For example a package may require a `config` entry in the container that is meant to resolve to an array.
This can be achieved by creating a delegate that creates an instance of `ArrayObject`:

```php
use ArrayObject;
use Auryn\Injector;
use Northwoods\Container\InjectorContainer;

// Share a global "config" array as an object
$injector->share('config')->delegate('config', function () {
    return new ArrayObject(require '/path/to/config.php');
});

// Create the container
$container = new InjectorContainer($injector);
```

Now whenever `$container->get('config')` is called the `ArrayObject` will be returned.

### Examples

Additional examples are available in the `examples/` directory.

## License

MIT
