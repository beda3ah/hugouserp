<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\URL;

// Models & observers
use App\Models\Product;
use App\Observers\ProductObserver;

// Services
use App\Services\Contracts\ProductServiceInterface;
use App\Services\ProductService;
use App\Services\Contracts\ModuleFieldServiceInterface;
use App\Services\ModuleFieldService;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
        $this->app->bind(ModuleFieldServiceInterface::class, ModuleFieldService::class);
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);
        JsonResource::withoutWrapping();

        if (config('app.force_https')) {
            URL::forceScheme('https');
        }

        if (app()->environment('local')) {
            Model::shouldBeStrict();
            Model::preventSilentlyDiscardingAttributes();
            Model::preventAccessingMissingAttributes();
            Model::preventLazyLoading();
        }

        // Observers
        Product::observe(ProductObserver::class);
    }
}
