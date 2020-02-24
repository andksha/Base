<?php

namespace Anso\Framework\Base;

use Anso\Framework\Contract\ExceptionHandler;
use ErrorException;

class Configuration
{
    protected string $configPath;

    public function __construct($relativeConfigPath = '/config')
    {
        $this->configPath = BASE_PATH . $relativeConfigPath;
        $this->configure();
    }

    public function configure(): Configuration
    {
        $this->configurePhp();

        return $this;
    }

    private function configurePhp(): void
    {
        set_error_handler(function ($code, $message, $file, $line) {
            throw new ErrorException($message, $code, $code, $file, $line);
        });
    }

    public function configPath(): string
    {
        return $this->configPath;
    }

    public function exceptionHandler(): ExceptionHandler
    {
        return include($this->configPath . "/exception_handler.php");
    }
}