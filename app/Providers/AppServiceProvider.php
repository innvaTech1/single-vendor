<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Setting;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        if (cache()->has('setting')) {
            $setting = cache()->get('setting');
        } else {
            $setting = Setting::first();
            cache()->forever('setting', $setting);
        }

        View::share('setting', $setting);
    }
}
