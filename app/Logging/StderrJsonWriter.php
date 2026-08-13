<?php

namespace App\Logging;

class StderrJsonWriter
{
    private static string $streamUri = 'php://stderr';

    /**
     * Write one JSON object directly to the process stderr stream.
     *
     * Unlike error_log(), this is not wrapped by PHP-FPM in a
     * "NOTICE: PHP message:" prefix, so VictoriaLogs can parse _msg.
     */
    public static function write(array $record): void
    {
        $encoded = json_encode(
            $record,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR
        );

        if (! is_string($encoded)) {
            $encoded = '{"_msg":"A log record could not be encoded.","level_name":"CRITICAL"}';
        }

        $stream = @fopen(self::$streamUri, 'ab');

        if (is_resource($stream)) {
            fwrite($stream, $encoded.PHP_EOL);
            fclose($stream);
        }
    }

    /** @internal Only for isolating writer output in unit tests. */
    public static function useStreamForTesting(?string $streamUri): void
    {
        self::$streamUri = $streamUri ?? 'php://stderr';
    }
}
