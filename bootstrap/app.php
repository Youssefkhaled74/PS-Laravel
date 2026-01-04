<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Register SetLocaleFromHeader for API routes (Laravel 12-style bootstrapping)
        // This prepends the middleware into the 'api' middleware group so it runs on API requests.
        // If your Laravel version exposes different middleware methods, adapt accordingly.
        $middleware->prependToGroup('api', \App\Http\Middleware\SetLocaleFromHeader::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
