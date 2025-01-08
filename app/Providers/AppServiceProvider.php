<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register a custom response macro
        Response::macro('apiResponse', function ($status, $message, $data = [], $status_code = 200) {
            return response()->json([
                'status'  => $status,
                'message' => $message,
                'data'    => $data,
            ], $status_code);
        });
        Vite::prefetch(concurrency: 3);
    }
}
