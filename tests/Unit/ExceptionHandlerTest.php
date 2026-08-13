<?php

namespace Tests\Unit;

use App\Exceptions\Handler;
use Illuminate\Container\Container;
use LogicException;
use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Stringable;

class ExceptionHandlerTest extends TestCase
{
    public function test_it_writes_the_original_exception_to_php_error_log_when_the_logger_fails(): void
    {
        $container = new Container();
        $container->instance(LoggerInterface::class, new class extends AbstractLogger {
            public function log($level, string|Stringable $message, array $context = []): void
            {
                throw new RuntimeException('Logger is unavailable');
            }
        });

        $errorLog = tempnam(sys_get_temp_dir(), 'paywin-error-log-');
        $previousErrorLog = ini_set('error_log', $errorLog);

        try {
            (new Handler($container))->report(new LogicException('Application failed'));

            $contents = file_get_contents($errorLog);

            $this->assertStringContainsString('Application failed', $contents);
            $this->assertStringContainsString('Logger is unavailable', $contents);
            $this->assertStringContainsString('logging_exception', $contents);
        } finally {
            ini_set('error_log', $previousErrorLog);
            unlink($errorLog);
        }
    }
}
