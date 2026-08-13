<?php

namespace Tests\Unit;

use App\Logging\VictoriaLogsJsonFormatter;
use DateTimeImmutable;
use Monolog\Level;
use Monolog\LogRecord;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class VictoriaLogsJsonFormatterTest extends TestCase
{
    public function test_it_preserves_exception_details_and_victoria_logs_message(): void
    {
        $exception = new RuntimeException('Diagnostic exception');
        $record = new LogRecord(
            new DateTimeImmutable(),
            'testing',
            Level::Error,
            'Request failed',
            ['exception' => $exception, 'request_id' => 'request-123'],
        );

        $formatted = (new VictoriaLogsJsonFormatter())->format($record);
        $decoded = json_decode($formatted, true, 512, JSON_THROW_ON_ERROR);

        $this->assertSame('Request failed', $decoded['_msg']);
        $this->assertSame('request-123', $decoded['context']['request_id']);
        $this->assertSame(RuntimeException::class, $decoded['context']['exception']['class']);
        $this->assertSame('Diagnostic exception', $decoded['context']['exception']['message']);
    }
}
