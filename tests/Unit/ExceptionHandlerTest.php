<?php

namespace Tests\Unit;

use App\Exceptions\Handler;
use App\Logging\StderrJsonWriter;
use Illuminate\Container\Container;
use LogicException;
use PHPUnit\Framework\TestCase;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use RuntimeException;
use Stringable;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ExceptionHandlerTest extends TestCase
{
    public function test_it_writes_to_stderr_even_when_the_configured_logger_accepts_the_record(): void
    {
        $container = new Container();
        $container->instance(LoggerInterface::class, new class extends AbstractLogger {
            public function log($level, string|Stringable $message, array $context = []): void
            {
                // Simulate a null channel or a handler filtering this level.
            }
        });

        $contents = $this->captureStderr(function () use ($container) {
            (new Handler($container))->report(new LogicException('Filtered application failure'));
        });

        $this->assertStringContainsString('Filtered application failure', $contents);
        $this->assertStringContainsString('stderr-exception-mirror', $contents);
    }

    public function test_it_writes_the_original_exception_to_stderr_when_the_logger_fails(): void
    {
        $container = new Container();
        $container->instance(LoggerInterface::class, new class extends AbstractLogger {
            public function log($level, string|Stringable $message, array $context = []): void
            {
                throw new RuntimeException('Logger is unavailable');
            }
        });

        $contents = $this->captureStderr(function () use ($container) {
            (new Handler($container))->report(new LogicException('Application failed'));
        });

        $this->assertStringContainsString('Application failed', $contents);
        $this->assertStringContainsString('Logger is unavailable', $contents);
        $this->assertStringContainsString('logging_exception', $contents);
    }

    public function test_it_does_not_mirror_an_expected_not_found_exception_as_an_error(): void
    {
        $container = new Container();
        $container->instance(LoggerInterface::class, new class extends AbstractLogger {
            public function log($level, string|Stringable $message, array $context = []): void
            {
                // Laravel also ignores this expected exception.
            }
        });

        $contents = $this->captureStderr(function () use ($container) {
            (new Handler($container))->report(new NotFoundHttpException('Missing source map'));
        });

        $this->assertSame('', $contents);
    }

    private function captureStderr(callable $callback): string
    {
        $stderrLog = tempnam(sys_get_temp_dir(), 'paywin-stderr-log-');
        StderrJsonWriter::useStreamForTesting($stderrLog);

        try {
            $callback();

            return file_get_contents($stderrLog);
        } finally {
            StderrJsonWriter::useStreamForTesting(null);
            unlink($stderrLog);
        }
    }
}
