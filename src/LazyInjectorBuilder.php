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

        $configs = $this->makeConfigs($injector);
        $builder = new InjectorBuilder($configs);

        return $builder->build($injector);
    }

    private function makeConfigs(Injector $injector): array
    {
        return array_map(
            static function (string $config) use ($injector): InjectorConfig {
                return $injector->make($config);
            },
            $this->configs
        );
    }
}
