<?php

namespace Northwoods\Container;

use Auryn\Injector;

class InjectorBuilder
{
    /**
     * @var InjectorConfig[]
     */
    private $configs;

    /**
     * @param InjectorConfig[] $configs
     */
    public function __construct(array $configs = [])
    {
        $this->configs = $configs;
    }

    /**
     * Build the injector using the provided configuration
     *
     * @return Injector
     */
    public function build(Injector $injector = null)
    {
        if (empty($injector)) {
            $injector = new Injector();
        }

        // Apply configuration to the injector
        array_map($this->applicator($injector), $this->configs);

        return $injector;
    }

    /**
     * @return callable
     */
    private function applicator(Injector $injector)
    {
        return static function (InjectorConfig $config) use ($injector) {
            return $config->apply($injector);
        };
    }
}
