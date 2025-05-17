<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ApiRouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // This tells Laravel: “Load routes/api.php, wrap them in ‘api’ middleware,
        // and automatically prefix every URI with /api”.
        Route::middleware('api')
             ->prefix('api')
             ->group(base_path('routes/api.php'));
    }
}
