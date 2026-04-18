<?php
// AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use App\Helpers\PermissionHelper;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Blade::directive('can', function ($expression) {
            return "<?php if (app(App\Helpers\PermissionHelper::class)->can({$expression})): ?>";
        });

        Blade::directive('endcan', function () {
            return "<?php endif; ?>";
        });

        Blade::directive('hasrole', function ($expression) {
            return "<?php if (app(App\Helpers\PermissionHelper::class)->hasRole({$expression})): ?>";
        });

        Blade::directive('endhasrole', function () {
            return "<?php endif; ?>";
        });
    }
}
