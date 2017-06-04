<?php

require __DIR__ . '/../vendor/autoload.php';

use Auryn\Injector;
use Northwoods\Container\InjectorContainer;
use Northwoods\Container\Fixture\ClassWithoutParameters;

/**
 * Config object that acts like an array.
 */
class Configuration extends ArrayObject
{
    public function __construct()
    {
        parent::__construct(include __DIR__ . '/config.inc.php');
    }
}

// Set up the injector
$injector = new Injector();

// Container requires a "config" identifier, point it to our Configuration class.
$injector->alias('config', Configuration::class);

// Create the container
$container = new InjectorContainer($injector);

// The "config" identifier is now usable without a class named "config".
$config = $container->get('config');

var_dump($config['test']);
