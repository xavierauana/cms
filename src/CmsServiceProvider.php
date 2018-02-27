<?php

namespace Anacreation\Cms;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class CmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {

        Blade::doubleEncode();

        $this->loadRoutesFrom(__DIR__ . '/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        $this->views();
        $this->config();
        $this->defaultTheme();
        $this->defaultAsset();
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

    private function config() {
        $this->publishes([
            __DIR__ . '/config/theme.php' => config_path('theme.php'),
            __DIR__ . '/config/cms.php'   => config_path('cms.php'),
            __DIR__ . '/config/lfm.php'   => config_path('lfm.php'),
        ]);
    }

    private function defaultTheme() {
        $this->publishes([
            __DIR__ . '/themes' => resource_path('views/themes'),
        ]);
    }

    private function defaultAsset() {
        $this->publishes([
            __DIR__ . '/src/public/css/cms' => public_path('css/cms'),
            __DIR__ . '/src/public/js/cms'  => public_path('js/cms'),
            __DIR__ . '/fonts'              => public_path('fonts'),
        ]);
    }
}
