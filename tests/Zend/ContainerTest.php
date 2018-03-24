<?php
declare(strict_types=1);

namespace Northwoods\Container\Zend;

use Auryn\Injector;
use Northwoods\Container\InjectorConfig;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Zend\ContainerConfigTest as ZendTest;

class ContainerTest extends TestCase
{
    use ZendTest\AllTestTrait;

    protected function createContainer(array $config): ContainerInterface
    {
        $factory = new ContainerFactory();

        return $factory(new Config(['dependencies' => $config]));
    }
}
