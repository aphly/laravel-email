<?php

namespace Aphly\LaravelEmail;

use Aphly\Laravel\Providers\ServiceProvider;

class EmailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */

    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //$this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'laravel-email');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }

}
