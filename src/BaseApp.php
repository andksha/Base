<?php

namespace Anso\Framework\Base;

use Anso\Framework\Contract\Application;
use Anso\Framework\Contract\Container;

abstract class BaseApp implements Application
{
    protected Container $container;
    protected Configuration $configuration;

    public function __construct(Container $container, Configuration $configuration)
    {
        $this->container = $container;
        $this->configuration = $configuration;
    }

    public function bind(string $abstract, $concrete): void
    {
        $this->container->bind($abstract, $concrete);
    }

    public function singleton(string $abstract, $concrete): void
    {
        $this->container->singleton($abstract, $concrete);
    }

    public function make(string $class, array $parameters = [])
    {
        return $this->container->make($class, $parameters);
    }

    public function addResolved(string $abstract, $instance): Container
    {
        return $this->container->addResolved($abstract, $instance);
    }

    public function stop(): void
    {
        return;
    }
}