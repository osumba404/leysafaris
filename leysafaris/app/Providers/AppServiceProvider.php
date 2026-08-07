<?php

namespace App\Providers;

use App\Models\Setting;
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
        View::composer(['layouts.public', 'contact', 'about.index'], function ($view) {
            if (! array_key_exists('settings', $view->getData())) {
                $view->with('settings', Setting::allGrouped());
            }
        });
    }
}
