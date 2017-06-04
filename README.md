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

## License

MIT
