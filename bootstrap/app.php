<?php

use Illuminate\Foundation\Application;
use App\Http\Middleware\CaregiverMiddleware;
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
        $middleware->alias([
            'admin'         => \App\Http\Middleware\AdminMiddleware::class,
            'family_parent' => \App\Http\Middleware\FamilyParentMiddleware::class,
            'caregiver' => CaregiverMiddleware::class,
        ]);
    })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
