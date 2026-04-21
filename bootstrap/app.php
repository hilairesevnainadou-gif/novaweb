<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route; // Ajoutez cette ligne IMPORTANTE

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',

        /*
        |──────────────────────────────────────────────────────
        | Le callback `then` permet de charger des fichiers de
        | routes supplémentaires APRÈS les routes standard.
        | C'est ici que admin.php et webhooks.php sont chargés.
        | Sans ce bloc, les routes admin.* n'existent pas et
        | l'extension VS Code affiche "Route not found".
        |──────────────────────────────────────────────────────
        */
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));

            Route::middleware('web')
                ->group(base_path('routes/webhooks.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'disable.registration' => \App\Http\Middleware\DisableRegistration::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role'       => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
