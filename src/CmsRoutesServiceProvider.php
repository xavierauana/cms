<?php

namespace Anacreation\Cms;

use Illuminate\Support\ServiceProvider;

class CmsRoutesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        $this->loadRoutesFrom(__DIR__ . '/routes.php');
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
        $this->mergeConfigFrom(
            __DIR__ . '/config/theme.php', 'theme'
        );
    }
}
