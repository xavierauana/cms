<?php

namespace Anacreation\Cms;

use Anacreation\Cms\Console\Kernel;
use Anacreation\Cms\Contracts\CmsPageInterface as Page;
use Anacreation\Cms\Handler\Handler;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class CmsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot() {

        if ($this->requestFrontend()) {
            $this->registerFrontendViewComposers();
        }

        $this->loadMigrationsFrom(__DIR__.'/migrations');

        $this->views();

        $this->config();

        $this->defaultAsset();

        $this->registerBindings();

        if (config('cms.enable_scheduler', false)) {
            app()->singleton('CmsScheduler', function ($app) {
                $dispatcher = $app->make(\Illuminate\Contracts\Events\Dispatcher::class);

                return new Kernel($app, $dispatcher);
            });

            app()->make('CmsScheduler');
        }
    }

    /**
     * Register any application services.
     */
    public function register() {
        $this->mergeConfigFrom(
            __DIR__.'/config/cms.php', 'cms'
        );
        $this->mergeConfigFrom(
            __DIR__.'/config/admin.php', 'admin'
        );

        $this->registerBindings();
    }

    private function views() {
        $this->loadViewsFrom(__DIR__.'/views', 'cms');

        $this->publishes([
                             __DIR__.'/views' => resource_path('views/vendor/cms'),
        ], 'backend_views');
    }

    private function config() {
        $this->publishes([
                             __DIR__.'/config/cms.php'   => config_path('cms.php'),
                             __DIR__.'/config/lfm.php'   => config_path('lfm.php'),
                             __DIR__.'/config/admin.php' => config_path('admin.php'),
        ], 'config');
    }

    private function defaultAsset() {
        $this->publishes([
                             __DIR__.'/public/css'      => public_path('css/cms'),
                             __DIR__.'/public/js'       => public_path('js/cms'),
                             __DIR__.'/public/ckeditor' => public_path('vendor/cms/ckeditor'),
                             __DIR__.'/../fonts'        => public_path('fonts'),
                             __DIR__.'/seeds'           => database_path('seeds'),
                             __DIR__.'/themes'          => resource_path('views/themes'),
        ], 'assets');

        $this->publishes([
                             __DIR__.'/resources' => resource_path('cms'),
        ], 'dev');
    }

    private function registerBindings() {
        app()->bind(ExceptionHandler::class, Handler::class);

        foreach (config('cms.bindings') as $abstract => $implementation) {
            app()->bind($abstract, $implementation);
        }

        Route::model('page', get_class(app(Page::class)));

        app()->singleton('CmsPlugins', function () {
            return [];
        });
    }

    private function requestFrontend(): bool {
        $fistSegment = (request()->segments())[0] ?? null;

        return $fistSegment !== config("admin.route_prefix");
    }

    private function registerFrontendViewComposers() {
        foreach (config('cms.view_composer',[]) as $view => $composer) {
            View::composer($view, $composer);
        }
    }
}
