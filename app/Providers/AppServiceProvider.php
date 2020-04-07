<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Thing;
use App\Observers\ThingObserver;

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
        Thing::observe(ThingObserver::class);
    }
}
