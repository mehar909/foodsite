<?php

namespace App\Providers;

use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteRegistrar extends ServiceProvider
{
    public function boot(Registrar $router): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }
}
