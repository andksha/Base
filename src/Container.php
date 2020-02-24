<?php

namespace Anso\Framework\Base;

use Anso\Framework\Contract\Container as ContainerContract;
use Closure;
use ReflectionClass;
use ReflectionException;
use ReflectionParameter;

class Container implements ContainerContract
{
    protected Configuration $configurator;
    protected array $bindings;
    protected array $singletons;
    protected array $resolved;
    protected array $providers;

    public function __construct(Configuration $configurator)
    {
        $this->addResolved(ContainerContract::class, $this);
        $this->addResolved(Configuration::class, $configurator);
        $this->configurator = $configurator->configure();

        $this->registerBindings($this->createProviders());
    }

    protected function createProviders(): array
    {
        $config = include($this->configurator->configPath() . '/providers.php');

        foreach ($config as $item) {
            $this->providers[] = new $item($this);
        }

        return $this->providers;
    }

    protected function registerBindings(array $providers): void
    {
        foreach ($providers as $provider) {
            $provider->register();
        }
    }

    public function bind(string $abstract, $concrete): void
    {
        $this->bindings[$abstract] = $concrete;
    }

    public function singleton(string $abstract, $concrete): void
    {
        $this->singletons[$abstract] = $concrete;
    }

    /**
     * @inheritDoc
     */
    public function make(string $class, array $parameters = [])
    {
        if ($this->isSingleton($class)) {
            return $this->resolveSingleton($class, $parameters);
        }

        if (!$this->isBound($class)) {
            return $this->build($class, $parameters);
        }

        return $this->build($this->bindings[$class], $parameters);
    }

    public function addResolved(string $abstract, $instance): ContainerContract
    {
        $this->resolved[$abstract] = $instance;

        return $this;
    }

    protected function isSingleton(string $class): bool
    {
        return isset($this->singletons[$class]);
    }

    /**
     * @param string $class
     * @param array $parameters
     * @return mixed
     * @throws BindingException
     * @throws ReflectionException
     */
    protected function resolveSingleton(string $class, array $parameters = [])
    {
        if (!isset($this->resolved[$class])) {
            $this->resolved[$class] = $this->build($this->singletons[$class], $parameters);
        }

        return $this->resolved[$class];
    }

    protected function isBound(string $class): bool
    {
        return isset($this->bindings[$class]);
    }

    /**
     * @param string $concrete
     * @param array $parameters
     * @return object
     * @throws BindingException
     * @throws ReflectionException
     */
    protected function build($concrete, array $parameters = [])
    {
        if ($concrete instanceof Closure) {
            return $concrete($parameters);
        }

        try {
            $reflector = new ReflectionClass($concrete);
        } catch (ReflectionException $e) {
            throw new BindingException($e->getMessage());
        }

        if (!$reflector->isInstantiable()) {
            throw new BindingException("Target class $concrete can not be instantiated");
        }

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();
        $instances = $this->resolveDependencies($dependencies);

        $instance = $reflector->newInstanceArgs($instances);

        return $instance;
    }

    /**
     * @param array $dependencies
     * @return array
     * @throws BindingException
     * @throws ReflectionException
     */
    protected function resolveDependencies(array $dependencies)
    {
        $results = [];

        foreach ($dependencies as $dependency) {
            $results[] = is_null($dependency->getClass())
                ? $this->resolvePrimitive($dependency)
                : $this->resolveClass($dependency);
        }

        return $results;
    }

    /**
     * @param ReflectionParameter $parameter
     * @return ReflectionParameter
     * @throws BindingException
     * @throws ReflectionException
     */
    protected function resolvePrimitive(ReflectionParameter $parameter)
    {
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }

        throw new BindingException("Can not resolve $parameter of {$parameter->getDeclaringClass()->getName()}");
    }

    /**
     * @param ReflectionParameter $parameter
     * @return mixed
     * @throws BindingException
     * @throws ReflectionException
     */
    protected function resolveClass(ReflectionParameter $parameter)
    {
        try {
            return $this->make($parameter->getClass()->name);
        } catch (BindingException $e) {
            if ($parameter->isOptional()) {
                return $parameter->getDefaultValue();
            }

            throw $e;
        }
    }
}