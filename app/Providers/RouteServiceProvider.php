<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Branch;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // API throttle
        RateLimiter::for('api', function (Request $request) {
            $key = optional($request->user())->getKey() ?: $request->ip();
            return [ Limit::perMinute(120)->by($key) ];
        });

        // Parameter patterns
        Route::pattern('id', '[0-9]+');

        // Simple binding for {branch}
        Route::bind('branch', function ($value) {
            return Branch::query()->findOrFail($value);
        });
    }
}
