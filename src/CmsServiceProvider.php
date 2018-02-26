<?php

namespace Anacreation\Cms;

use Illuminate\Support\ServiceProvider;

class CmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {

        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        $this->views();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        //
    }

    private function views() {
        $this->loadViewsFrom(__DIR__ . '/views', 'cms');

        $this->publishes([
            __DIR__ . '/views' => resource_path('views/vendor/cms'),
        ]);
    }
}
