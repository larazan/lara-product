<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\redirectAdmin;
use App\Http\Middleware\SetLocaleMiddleware;

use Inertia\Inertia;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            SetLocaleMiddleware::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        //
        // alias
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'redirectAdmin' => redirectAdmin::class,
            // 'Captcha' => 'Mews\Captcha\Facades\Captcha',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
        $exceptions->render(function (NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return null;
            }

            return Inertia::render('Errors/404')
                ->toResponse($request)
                ->setStatusCode(404);
        });

        $exceptions->render(function (Throwable $e, $request) {
            if ($request->expectsJson() || ! app()->environment('production')) {
                return null;
            }

            return Inertia::render('Errors/500')
                ->toResponse($request)
                ->setStatusCode(500);
        });
    })->create();
