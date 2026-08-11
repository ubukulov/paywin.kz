<?php

namespace App\Logging;

use Monolog\Formatter\JsonFormatter;
use Monolog\LogRecord;

class VictoriaLogsJsonFormatter extends JsonFormatter
{
    /**
     * Format Monolog records as JSON accepted by VictoriaLogs.
     */
    public function format(LogRecord $record): string
    {
        $normalized = $this->normalizeRecord($record);
        $normalized['_msg'] = (string) ($normalized['message'] ?? '');

        return $this->toJson($normalized, true).($this->appendNewline ? "\n" : '');
    }
}
