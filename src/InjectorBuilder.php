<?php
declare(strict_types=1);

namespace Northwoods\Container;

use Auryn\Injector;

class InjectorBuilder
{
    /** @var InjectorConfig[] */
    private $configs;

    /**
     * @param InjectorConfig[] $configs
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

        // Apply configuration to the injector
        array_map($this->applicator($injector), $this->configs);

        return $injector;
    }

    private function applicator(Injector $injector): callable
    {
        return static function (InjectorConfig $config) use ($injector) {
            return $config->apply($injector);
        };
    }
}
