<?php

namespace Northwoods\Container\Config;

use Auryn\Injector;
use Eloquent\Phony\Phpunit\Phony;
use Northwoods\Container\Fixture\DelegatedClass;
use Northwoods\Container\Fixture\DelegateFactory;
use Northwoods\Container\Fixture\InvokableClass;
use Northwoods\Container\Fixture\PreparedClass;
use Northwoods\Container\Fixture\PreparedPrep;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ServiceConfigTest extends TestCase
{
    /**
     * @dataProvider dataConfig
     */
    public function testConfig(array $modifications, array $shared)
    {
        $services = [
            'aliases' => [
                'i' => InvokableClass::class,
            ],
            'delegators' => [
                PreparedClass::class => [
                    function (ContainerInterface $container, $service, callable $callable) {
                        $instance = $callable();
                        $this->assertSame(PreparedClass::class, $service);
                        $this->assertInstanceOf(PreparedClass::class, $instance);
                        return $instance;
                    },
                    PreparedPrep::class,
                ],
            ],
            'factories' => [
                DelegatedClass::class => DelegateFactory::class,
            ],
            'invokables' => [
                InvokableClass::class => InvokableClass::class,
            ],
            'services' => [
                ServiceConfigTest::class => $this,
            ],
        ];

        $services = array_replace($services, $modifications);

        // Mock
        $container = Phony::mock(ContainerInterface::class);

        $injector = new Injector();
        $injector->share($container->get());
        $injector->alias(ContainerInterface::class, $container->className());

        // Execute
        $config = new ServiceConfig($services);
        $config->apply($injector);

        // Verify
        $this->assertInstanceOf(
            InvokableClass::class,
            $injector->make('i'),
            'It handles aliases.'
        );

        $this->assertInstanceOf(
            PreparedClass::class,
            $injector->make(PreparedClass::class),
            'It handles delegators.'
        );

        $this->assertInstanceOf(
            DelegatedClass::class,
            $injector->make(DelegatedClass::class),
            'It handles factories.'
        );

        $this->assertInstanceOf(
            InvokableClass::class,
            $injector->make(InvokableClass::class),
            'It handles invokables.'
        );

        $this->assertSame(
            $this,
            $injector->make(ServiceConfigTest::class),
            'It handles services.'
        );

        // Verify sharing configuration
        array_map(
            function ($identifier) use ($injector, $shared) {
                if (in_array($identifier, $shared)) {
                    $this->assertSame(
                        $injector->make($identifier),
                        $injector->make($identifier),
                        "$identifier should be shared."
                    );
                } else {
                    $this->assertNotSame(
                        $injector->make($identifier),
                        $injector->make($identifier),
                        "$identifier should not be shared."
                    );
                }
            },
            [
                'i',
                PreparedClass::class,
                DelegatedClass::class,
                InvokableClass::class,
                ServiceConfigTest::class,
            ]
        );
    }

    public function dataConfig()
    {
        return [
            // modifications, shared
            'all shared by default' => [
                [], 
                [
                    'i',
                    PreparedClass::class,
                    DelegatedClass::class,
                    InvokableClass::class,
                    ServiceConfigTest::class,
                ],
            ],
            'none shared by default' => [
                [
                    'shared_by_default' => false,
                ],
                [
                    // Services are always shared because they are instances!
                    ServiceConfigTest::class,
                ],
            ],
            'some disabled shared' => [
                [
                    'shared' => [
                        DelegatedClass::class => false,
                        InvokableClass::class => false,
                    ],
                ],
                [
                    PreparedClass::class,
                    // Services are always shared because they are instances!
                    ServiceConfigTest::class,
                ],
            ],
            'some enabled shared' => [
                [
                    'shared_by_default' => false,
                    'shared' => [
                        DelegatedClass::class => true,
                    ],
                ],
                [
                    DelegatedClass::class,
                    // Services are always shared because they are instances!
                    ServiceConfigTest::class,
                ],
            ]
        ];
    }
}
