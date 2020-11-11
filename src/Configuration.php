<?php

namespace Anso\Framework\Base;

use Anso\Framework\Base\Contract\ExceptionHandler;
use ErrorException;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

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

    public function getValue(string $filename, string $key)
    {
        if ($array = file_get_contents($this->checkExtension($filename))) {
            throw new FileNotFoundException($filename);
        }

        return $array[$key] ?? null;
    }

    public function getFile(string $filename): array
    {
        if ($array = file_get_contents($this->checkExtension($filename))) {
            throw new FileNotFoundException($filename);
        }

        return $array;
    }

    private function checkExtension(string $filename)
    {
        return strstr('test.php', '.') ? $filename : $filename . '.php';
    }
}