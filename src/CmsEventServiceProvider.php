<?php

namespace Anacreation\Cms;

use Anacreation\Cms\Events\LanguageDeleted;
use Anacreation\Cms\Events\LanguageSaved;
use Anacreation\Cms\Events\LinkDeleted;
use Anacreation\Cms\Events\LinkSaved;
use Anacreation\Cms\Events\MenuDeleted;
use Anacreation\Cms\Events\MenuSaved;
use Anacreation\Cms\Events\PageDeleted;
use Anacreation\Cms\Events\PageSaved;
use Anacreation\Cms\Listeners\UpdateCacheHandler;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class CmsEventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        MenuSaved::class       => [
            UpdateCacheHandler::class,
        ],
        MenuDeleted::class     => [
            UpdateCacheHandler::class,
        ],
        LinkSaved::class       => [
            UpdateCacheHandler::class,
        ],
        LinkDeleted::class     => [
            UpdateCacheHandler::class,
        ],
        LanguageSaved::class   => [
            UpdateCacheHandler::class,
        ],
        LanguageDeleted::class => [
            UpdateCacheHandler::class,
        ],
        PageSaved::class       => [
            UpdateCacheHandler::class,
        ],
        PageDeleted::class     => [
            UpdateCacheHandler::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot() {
        parent::boot();

        //
    }
}
