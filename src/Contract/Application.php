<?php

namespace Anso\Framework\Base\Contract;

interface Application extends Container
{
    public function start(): void;

    public function stop(): void;
}