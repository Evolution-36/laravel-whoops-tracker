<?php

namespace Evolution36\WhoopsTracker;

use Evolution36\WhoopsTracker\Models\LwtWhoops;
use Illuminate\Support\Facades\Route;

class WhoopsTrackerServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Abstract type to bind whoops-tracker as in the Service Container.
     *
     * @var string
     */
    public static $abstract = 'whoops-tracker';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->publishes([
            __DIR__.'/public' => public_path('vendor/whoops-tracker'),
        ], 'public');
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'lwt');
        $this->publishes([
            __DIR__.'/resources/views' => resource_path('views/vendor/whoops-tracker'),
        ], 'views');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(static::$abstract, function () {
            return new WhoopsTracker();
        });
    }
}
