<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

register_shutdown_function(static function (): void {
    $error = error_get_last();
    $fatalTypes = [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR];

    if (! $error || ! in_array($error['type'], $fatalTypes, true)) {
        return;
    }

    $message = sprintf(
        'PHP fatal error: %s in %s:%d',
        $error['message'],
        $error['file'],
        $error['line']
    );

    App\Logging\StderrJsonWriter::write([
        'message' => $message,
        'context' => [
            'fatal_error' => $error,
            'http_method' => $_SERVER['REQUEST_METHOD'] ?? null,
            'http_path' => parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH),
        ],
        'level' => 500,
        'level_name' => 'CRITICAL',
        'channel' => 'stderr-php-fatal',
        'datetime' => date(DATE_ATOM),
        '_msg' => $message,
    ]);
});

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
