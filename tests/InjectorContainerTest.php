<?php

namespace Northwoods\Container;

use Auryn\Injector;
use Northwoods\Container\Fixture\ClassThatDoesNotExist;
use Northwoods\Container\Fixture\ClassWithoutParameters;
use Northwoods\Container\Fixture\ClassWithParameters;
use PHPUnit\Framework\TestCase;

class InjectorContainerTest extends TestCase
{
    /**
     * @var Injector
     */
    private $injector;

    /**
     * @var InjectorContainer
     */
    private $container;

    public function setUp()
    {
        $this->injector = new Injector();
        $this->container = new InjectorContainer($this->injector);
    }

    public function testContainer()
    {
        $this->assertInstanceof(
            '\Psr\Container\ContainerInterface',
            $this->container,
            'It is a container implementation.'
        );
    }

    public function testHas()
    {
        $this->assertTrue(
            $this->container->has(ClassWithParameters::class),
            'It has classes that exist.'
        );

        $this->assertFalse(
            $this->container->has(ClassThatDoesNotExist::class),
            'It does not have invalid classes.'
        );
    }

    public function testGet()
    {
        $this->assertInstanceof(
            ClassWithoutParameters::class,
            $this->container->get(ClassWithoutParameters::class),
            'It gets instances of the same type.'
        );
    }

    public function testGetAlias()
    {
        $this->injector->alias('service', ClassWithoutParameters::class);

        $this->assertInstanceof(
            ClassWithoutParameters::class,
            $this->container->get('service'),
            'It gets instances of aliases.'
        );
    }

    public function testGetNotFound()
    {
        $this->assertTrue(
            is_subclass_of(
                NotFoundException::class,
                '\Psr\Container\NotFoundExceptionInterface'
            ),
            'NotFoundException implements NotFoundExceptionInterface'
        );

        $this->expectException(NotFoundException::class);

        $x = $this->container->get(ClassThatDoesNotExist::class);
    }

    public function testGetFailure()
    {
        $this->assertTrue(
            is_subclass_of(
                ContainerException::class,
                '\Psr\Container\ContainerExceptionInterface'
            ),
            'ContainerException implements ContainerExceptionInterface'
        );

        $this->expectException(ContainerException::class);

        $x = $this->container->get(ClassWithParameters::class);
    }
}
