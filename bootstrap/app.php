<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: [
            __DIR__ . '/../routes/web.php',
            __DIR__ . '/../routes/web/product.php',
            __DIR__ . '/../routes/web/product_size.php',
            __DIR__ . '/../routes/web/client.php',
            __DIR__ . '/../routes/web/outflow.php',
            __DIR__ . '/../routes/web/notification.php',
        ],
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->validateCsrfTokens(except: [
            // 'products/',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
