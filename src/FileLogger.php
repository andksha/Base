<?php

namespace Anso\Framework\Base;

use Anso\Framework\Contract\Logger;
use DateTime;

class FileLogger implements Logger
{
    public function log(string $data): void
    {
        $currentDateTime = new DateTime();
        $date = $currentDateTime->format('d-m-Y');
        $timestamp = $currentDateTime->format('H:i:s');

        $logDir = BASE_PATH . '/logs';

        $logFile = fopen($logDir . '/log-' . $date . '.log', 'a');
        fwrite($logFile, $timestamp . ": " . $data . "\n\n");
        fclose($logFile);
    }
}