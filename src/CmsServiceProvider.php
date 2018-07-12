<?php

namespace Anacreation\Cms;

use Anacreation\Cms\Handler\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
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

        $this->loadMigrationsFrom(__DIR__ . '/migrations');

        $this->registerBindings();

        $this->views();

        $this->config();

        $this->defaultAsset();

        app()->bind(ExceptionHandler::class, Handler::class);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {
        $this->mergeConfigFrom(
            __DIR__ . '/config/cms.php', 'cms'
        );
        $this->mergeConfigFrom(
            __DIR__ . '/config/admin.php', 'admin'
        );
    }

    private function views() {
        $this->loadViewsFrom(__DIR__ . '/views', 'cms');

        $this->publishes([
            __DIR__ . '/views' => resource_path('views/vendor/cms'),
        ], 'backend_views');

    }

    private function config() {
        $this->publishes([
            __DIR__ . '/config/cms.php'   => config_path('cms.php'),
            __DIR__ . '/config/lfm.php'   => config_path('lfm.php'),
            __DIR__ . '/config/admin.php' => config_path('admin.php'),
        ], 'config');
    }

    private function defaultAsset() {
        $this->publishes([
            __DIR__ . '/public/css'            => public_path('css/cms'),
            __DIR__ . '/public/js'             => public_path('js/cms'),
            __DIR__ . '/public/ckeditor'       => public_path('vendor/ckeditor'),
            __DIR__ . '/public/src-noconflict' => public_path('js/src-noconflict'),
            __DIR__ . '/../fonts'              => public_path('fonts'),
            __DIR__ . '/seeds'                 => database_path('seeds'),
            __DIR__ . '/themes'                => resource_path('views/themes'),
        ], 'assets');

        $this->publishes([
            __DIR__ . '/resources' => resource_path('cms')
        ], 'dev');
    }

    private function registerBindings() {
        foreach (config("cms.bindings") as $abstract => $implementation) {
            app()->bind($abstract, $implementation);
        }
    }
}
