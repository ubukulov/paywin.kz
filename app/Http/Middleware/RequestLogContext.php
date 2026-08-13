<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RequestLogContext
{
    /**
     * Attach safe request metadata to every application log record.
     */
    public function handle(Request $request, Closure $next)
    {
        $requestId = $request->headers->get('X-Request-ID');

        if (! is_string($requestId) || ! preg_match('/^[A-Za-z0-9][A-Za-z0-9._-]{0,127}$/', $requestId)) {
            $requestId = (string) Str::uuid();
        }

        $request->attributes->set('request_id', $requestId);

        Log::withContext([
            'request_id' => $requestId,
            'http_method' => $request->method(),
            'http_path' => $request->getPathInfo(),
            'client_ip' => $request->ip(),
        ]);

        $response = $next($request);
        $response->headers->set('X-Request-ID', $requestId);

        return $response;
    }
}
