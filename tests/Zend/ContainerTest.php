<?php
declare(strict_types=1);

namespace Northwoods\Container\Zend;

use Auryn\Injector;
use Northwoods\Container\InjectorConfig;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;
use Zend\ContainerConfigTest\AbstractExpressiveContainerConfigTest;

class ContainerTest extends AbstractExpressiveContainerConfigTest
{
    protected function createContainer(array $config): ContainerInterface
    {
        $factory = new ContainerFactory();

        return $factory(new Config(['dependencies' => $config]));
    }
}
