<?php

namespace App\Providers;

use App\Models\ManagementYear;
use App\Models\Ministry;
use App\Models\SiteSetting;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

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
        RateLimiter::for('public', fn($request) =>
            Limit::perMinute(60)->by($request->ip())
        );

        RateLimiter::for('auth', fn($request) =>
            Limit::perMinute(10)->by($request->ip())
        );

        RateLimiter::for('api-public', fn($request) =>
            Limit::perMinute(30)->by($request->ip())
        );

        // Bagikan data kabinet & settings ke semua views (untuk logo dinamis & navbar)
        View::composer('*', function ($view) {
            $year = ManagementYear::getActive();
            $ministries = $year ? Ministry::where('management_year_id', $year->id)->orderBy('sort_order')->get() : collect();
            $settings = SiteSetting::allAsArray();

            $view->with(compact('year', 'ministries', 'settings'));
        });
    }
}
