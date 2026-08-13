<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        $this->writeExceptionToStderr($exception);

        try {
            parent::report($exception);
        } catch (Throwable $loggingException) {
            $fallbackRecord = [
                '_msg' => 'The configured logger failed while reporting an application exception.',
                'level_name' => 'CRITICAL',
                'exception' => $this->emergencyExceptionContext($exception),
                'logging_exception' => $this->emergencyExceptionContext($loggingException),
            ];

            $encodedRecord = json_encode(
                $fallbackRecord,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR
            );

            error_log($encodedRecord ?: $fallbackRecord['_msg']);
        }
    }

    /**
     * Mirror exceptions directly to the container log. This path deliberately
     * bypasses Laravel's configured channels and levels: a production
     * LOG_CHANNEL=null or LOG_LEVEL=emergency must not hide HTTP 500 causes.
     */
    private function writeExceptionToStderr(Throwable $exception): void
    {
        $context = [
            'exception' => $this->emergencyExceptionContext($exception),
        ];

        if ($this->container->bound('request')) {
            $request = $this->container->make('request');
            $context += [
                'request_id' => $request->attributes->get('request_id'),
                'http_method' => $request->method(),
                'http_path' => $request->getPathInfo(),
                'client_ip' => $request->ip(),
            ];
        }

        $record = [
            'message' => $exception->getMessage(),
            'context' => $context,
            'level' => 400,
            'level_name' => 'ERROR',
            'channel' => 'stderr-exception-mirror',
            'datetime' => date(DATE_ATOM),
            '_msg' => $exception->getMessage(),
        ];

        $encodedRecord = json_encode(
            $record,
            JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PARTIAL_OUTPUT_ON_ERROR
        );

        error_log($encodedRecord ?: 'An application exception could not be encoded.');
    }

    /**
     * Build a small, serialization-safe exception payload for the emergency
     * stderr fallback. Do not call the configured logger from this path.
     */
    private function emergencyExceptionContext(Throwable $exception): array
    {
        return [
            'class' => get_class($exception),
            'message' => $exception->getMessage(),
            'code' => $exception->getCode(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
        ];
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        return parent::render($request, $exception);
    }
}
