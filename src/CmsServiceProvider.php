<?php

namespace Anacreation\Cms;

use Anacreation\Cms\Console\Commands\GenerateSitemap;
use Anacreation\Cms\Console\Commands\ReloadPhpFpm;
use Anacreation\Cms\Console\Commands\UpdateDefaultAppConfig;
use Anacreation\Cms\Contracts\CmsPageInterface as Page;
use Anacreation\Cms\Contracts\ICreateContentObjectFromRequest;
use Anacreation\Cms\Handler\Handler;
use Anacreation\Cms\Services\CreateContentObjectFromRequest;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Eloquent\Factory as EloquentFactory;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class CmsServiceProvider extends ServiceProvider
{

    private $commands = [
        ReloadPhpFpm::class,
        GenerateSitemap::class,
        UpdateDefaultAppConfig::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot() {

        if($this->requestFrontend()) {
            $this->registerFrontendViewComposers();
        }

        $this->loadMigrationsFrom(__DIR__.'/migrations');

        $this->views();

        $this->config();

        $this->defaultAsset();

        $this->registerBindings();

        $this->registerConsoleCommands();

        $this->registerBladeDirectives();

    }

    /**
     * Register any application services.
     */
    public function register() {
        $this->mergeConfigFrom(

            __DIR__.'/config/cms.php',
            'cms'
        );
        $this->mergeConfigFrom(
            __DIR__.'/config/admin.php',
            'admin'
        );

        $this->registerBindings();

        $this->loadDevResources();
    }

    private function views() {
        $this->loadViewsFrom(__DIR__.'/views',
                             'cms');

        $this->publishes([
                             __DIR__.'/views' => resource_path('views/vendor/cms'),
                         ],
                         'backend_views');
    }

    private function config() {
        $this->publishes([
                             __DIR__.'/config/cms.php'   => config_path('cms.php'),
                             __DIR__.'/config/lfm.php'   => config_path('lfm.php'),
                             __DIR__.'/config/admin.php' => config_path('admin.php'),
                         ],
                         'config');
    }

    private function defaultAsset() {
        $this->publishes([
                             __DIR__.'/public/css'      => public_path('css/cms'),
                             __DIR__.'/public/js'       => public_path('js/cms'),
                             __DIR__.'/public/ckeditor' => public_path('vendor/cms/ckeditor'),
                             __DIR__.'/../fonts'        => public_path('fonts'),
                             __DIR__.'/seeds'           => database_path('seeds'),
                             __DIR__.'/themes'          => resource_path('views/themes'),
                         ],
                         'assets');

        $this->publishes([
                             __DIR__.'/resources' => resource_path('cms'),
                         ],
                         'dev');
    }

    private function registerBindings() {

        app()->bind(ExceptionHandler::class,
                    Handler::class);

        foreach(config('cms.bindings') as $abstract => $implementation) {
            app()->bind($abstract,
                        $implementation);
        }

        app()->bind(ICreateContentObjectFromRequest::class,
                    CreateContentObjectFromRequest::class);

        Route::model('page',
                     get_class(app(Page::class)));
    }

    private function requestFrontend(): bool {
        $fistSegment = (request()->segments())[0] ?? null;

        return $fistSegment !== config("admin.route_prefix");
    }

    private function registerFrontendViewComposers() {
        foreach(config('cms.view_composer',
                       []) as $view => $composer) {
            View::composer($view,
                           $composer);
        }
    }

    private function registerBladeDirectives() {

    }

    private function registerConsoleCommands() {
        if($this->app->runningInConsole()) {
            $this->commands($this->commands);
        }
    }

    private function loadDevResources() {

    }
}
