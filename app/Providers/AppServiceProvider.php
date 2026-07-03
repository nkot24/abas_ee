<?php

namespace App\Providers;

use App\Services\FakeShippingService;
use App\Services\LatvijasPostsService;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
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

    public function boot(): void
    {
        RateLimiter::for('checkout', fn (Request $r) =>
            Limit::perMinute(5)->by($r->ip())
        );

        RateLimiter::for('address-search', fn (Request $r) =>
            Limit::perMinute(30)->by($r->ip())
        );

        RateLimiter::for('shipping-calc', fn (Request $r) =>
            Limit::perMinute(20)->by($r->ip())
        );

        RateLimiter::for('gdpr-delete', fn (Request $r) =>
            Limit::perHour(3)->by($r->ip())
        );
    }
}
