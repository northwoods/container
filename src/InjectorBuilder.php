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
        if ($injector === null) {
            $injector = new Injector();
        }

        foreach ($this->configs as $config) {
            $config->apply($injector);
        }

        return $injector;
    }
}
