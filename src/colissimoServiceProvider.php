<?php

namespace Quimeboule\Colissimo;

use Illuminate\Support\ServiceProvider;

class ColissimoServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/colissimo.php', 'colissimo');
        $this->mergeConfigFrom(__DIR__.'/../config/rules.php', 'colissimo.rules');
        $this->mergeConfigFrom(__DIR__.'/../config/prices.php', 'colissimo.prices');
        $this->mergeConfigFrom(__DIR__.'/../config/zones.php', 'colissimo.zones');
        $this->mergeConfigFrom(__DIR__.'/../config/insurances.php', 'colissimo.insurances');
        // Register the service the package provides.
        $this->app->singleton('colissimo', function ($app) {
            return new Colissimo;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['colissimo'];
    }
    
    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        $this->publishes([
            __DIR__.'/../config/colissimo.php' => config_path('colissimo.php'),
        ], 'colissimo.config');
    }
}
