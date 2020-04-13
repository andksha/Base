<?php

namespace Anso\Framework\Base\Contract;

use Anso\Framework\Base\BindingException;
use ReflectionException;

interface Container
{
    public function bind(string $abstract, $concrete): void;

    public function singleton(string $abstract, $concrete): void;

    /**
     * @param string $class
     * @param array $parameters
     * @return mixed
     * @throws BindingException
     * @throws ReflectionException
     */
    public function make(string $class, array $parameters = []);

    public function addResolved(string $abstract, $instance): self;
}