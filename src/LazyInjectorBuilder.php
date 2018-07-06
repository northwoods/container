<?php
declare(strict_types=1);

namespace Northwoods\Container;

use Auryn\Injector;

class LazyInjectorBuilder
{
    /** @var string[] */
    private $configs;

    /**
     * @param string[] $configs
     */
    public function __construct(array $configs = [])
    {
        $this->configs = $configs;
    }

    /**
     * Build the injector using the provided configuration.
     */
    public function build(Injector $injector = null): Injector
    {
        if (empty($injector)) {
            $injector = new Injector();
        }

        foreach ($this->configs as $config) {
            $this->makeConfig($injector, $config)->apply($injector);
        }

        return $injector;
    }

    private function makeConfig(Injector $injector, string $config): InjectorConfig
    {
        return $injector->make($config);
    }
}
