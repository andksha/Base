<?php

namespace Anso\Framework\Base\Contract;

use Throwable;

interface ExceptionHandler
{
    public function handle(Throwable $e);
}