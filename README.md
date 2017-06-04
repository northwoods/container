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

### Identifiers

[PSR-11][psr-11] does not require the container identifier to be a class name, while [Auryn][auryn] does.
The only exception to this rule in Auryn is that a [class alias][auryn-class-alias] can be anything.
These container "service names" must resolve to a class and will need to be aliased.

[auryn-class-alias]: https://github.com/rdlowrey/auryn#type-hint-aliasing

For example a package may require a `config` entry in the container that is meant to resolve to an array.
This can be achieved by creating a class that extends `ArrayObject` or implements `ArrayAccess`:

```php
namespace Acme;

class Configuration extends \ArrayObject {}
```

And then aliasing as `config` for the container:

```php
use Acme\Configuration;
use Auryn\Injector;
use Northwoods\Container\InjectorContainer;

// Create an alias to the class that acts as an array
$injector->alias('config', Configuration::class);

// Optional: Share an instance of Configuration globally
$injector->share(Configuration::class);

// Create the container
$container = new InjectorContainer($injector);
```

Now whenever `$container->get('config')` is called the `Configuration` instance will be returned.

### Examples

Additional examples are available in the `examples/` directory.

## License

MIT
