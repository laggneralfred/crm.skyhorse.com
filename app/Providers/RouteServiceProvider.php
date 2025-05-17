<?php


// app/Providers/RouteServiceProvider.php
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // existing code …
        parent::boot();

        /* 👇  add this —— only runs once on boot  */
        Route::middleware('api')
             ->prefix('api')
             ->group(base_path('routes/api.php'));
    }
}
