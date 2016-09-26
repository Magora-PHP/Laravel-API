<?php

namespace App\Providers;

use App\Services\SocialService;
use Illuminate\Support\ServiceProvider;


class SocialServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->singleton('App\Services\SocialService', function() {
            return new SocialService();
        });
    }
}
