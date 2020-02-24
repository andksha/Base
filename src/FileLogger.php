<?php

namespace Anso\Core;

use DateTime;

class FileLogger
{
    public function log($data): void
    {
        $currentDateTime = new DateTime();
        $date = $currentDateTime->format('YYYY-mm-dd');

        $logFile = fopen('log-' . $date . '.log', 'w');
        fwrite($logFile, $this->formatException());
        fclose($logFile);
    }
}