<?php

namespace App\Providers;

use App\Services\FakeShippingService;
use App\Services\LatvijasPostsService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton('shipping', function () {
            return config('shipping.driver') === 'fake'
                ? new FakeShippingService()
                : new LatvijasPostsService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
