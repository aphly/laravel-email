<?php

namespace Aphly\LaravelEmail;

use Aphly\Laravel\Providers\ServiceProvider;
use Aphly\LaravelEmail\Commands\Init;

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
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'laravel-email');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        if ($this->app->runningInConsole()) {
            $this->commands([
                Init::class,
            ]);
        }
    }

}
