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
