<?php

namespace Evolution36\WhoopsTracker;

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
