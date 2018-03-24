<?php

namespace Northwoods\Container\Zend;

use ArrayAccess;
use Auryn\Injector;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{
    /** @var Injector */
    private $injector;

    protected function setUp()
    {
        $this->injector = new Injector();
    }

    public function testInjectConfiguration()
    {
        $config = [
            'foo' => 'bar',
        ];

        (new Config($config))->apply($this->injector);

        $this->assertInstanceOf(ArrayAccess::class, $this->injector->make('config'));
        $this->assertSame($config, $this->injector->make('config')->getArrayCopy());
    }
}
