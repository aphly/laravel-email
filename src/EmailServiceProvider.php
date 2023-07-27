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
		$this->mergeConfigFrom(
            __DIR__.'/config/email.php', 'email'
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/email.php' => config_path('email.php'),
            __DIR__.'/public' => public_path('static/email')
        ]);
        //$this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/views', 'laravel-email');
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
    }

}
