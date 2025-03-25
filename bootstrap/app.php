<?php

use App\Http\Middleware\CheckPermissionRule;
use App\Http\Middleware\CheckUserRole;
use App\Http\Middleware\Localization;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Session\Middleware\StartSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/Web/web.php',
        api: __DIR__.'/../routes/Api/v1/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'permission' => CheckPermissionRule::class,
            'role' => CheckUserRole::class
        ]);
        $middleware->web(append: [
            Localization::class,
        ]);
        $middleware->api(append: [
            ValidateCsrfToken::class,
            StartSession::class,
            EncryptCookies::class
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
