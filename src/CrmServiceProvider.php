<?php

namespace Kalimeromk\Crm;

use Illuminate\Support\ServiceProvider;

class CrmServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {

        $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/crm.php' => config_path('crm.php'),
                __DIR__.'/../wsdl' => storage_path('app/public/wsdl'),
                ], 'config');
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/crm.php', 'crm');

        // Register the main class to use with the facade
        $this->app->singleton('crm', function (): Crm {
            return new Crm;
        });
    }
}
