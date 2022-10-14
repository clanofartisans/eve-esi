<?php

namespace Clanofartisans\EveEsi;

use Illuminate\Support\ServiceProvider;

class EveESIServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'clanofartisans');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'clanofartisans');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/Auth/routes.php');

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
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/eve-esi.php', 'eve-esi');

        // Register the service the package provides.
        $this->app->singleton('eve-esi', function ($app) {
            return new EveESI;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['eve-esi'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/eve-esi.php' => config_path('eve-esi.php'),
        ], 'eve-esi.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/clanofartisans'),
        ], 'eve-esi.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/clanofartisans'),
        ], 'eve-esi.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/clanofartisans'),
        ], 'eve-esi.views');*/

        // Registering package commands.
        $this->commands([
            \Clanofartisans\EveEsi\Commands\ESIOrders::class,
            \Clanofartisans\EveEsi\Commands\ESIUpdate::class,
            \Clanofartisans\EveEsi\Commands\YamsUpdate::class
        ]);
    }
}
