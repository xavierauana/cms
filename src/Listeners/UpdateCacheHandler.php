<?php

namespace Anacreation\Cms\Listeners;


use Anacreation\Cms\CacheKey;
use Anacreation\Cms\Contracts\CacheManageableInterface;
use Anacreation\CMS\Events\CacheManageableEvent;
use Anacreation\Cms\Events\LanguageDeleted;
use Anacreation\Cms\Events\LanguageSaved;
use Illuminate\Support\Facades\Cache;
use InvalidArgumentException;

class UpdateCacheHandler
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct() {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Event $event
     * @return void
     */
    public function handle(CacheManageableEvent $event) {
        if ($this->isPageEvent($event)) {
            $this->invalidatePageCache($event->manageableObject);
        } elseif ($this->isLanguageEvent($event)) {
            $this->invalidateLanguageCache();
        } else {
            $this->invalidateCache($event->manageableObject);
        }
    }

    /**
     * @param string|CacheManageableInterface $param
     */
    private function invalidateCache($param): void {
        if (is_string($param)) {
            $key = $param;
        } elseif ($param instanceof CacheManageableInterface) {
            $key = $param->getCacheKey();
        } else {
            throw new InvalidArgumentException("Invalided cache model!");
        }

        if (Cache::has($key)) {
            Cache::forget($key);
        }
    }

    private function isPageEvent($event) {
        return strpos(get_class($event), "Page") > -1;
    }

    private function invalidatePageCache(
        CacheManageableInterface $manageableObject
    ) {

        $keys = [
            $manageableObject->getCacheKey(),
            CacheKey::TOP_LEVEL_ACTIVE_PAGES,
            CacheKey::ACTIVE_PAGES
        ];
        foreach ($keys as $key) {
            $this->invalidateCache($key);
        }
    }

    private function isLanguageEvent($event) {
        return get_class($event) === LanguageSaved::class or get_class($event) === LanguageDeleted::class;
    }

    private function invalidateLanguageCache() {

        $keys = [
            CacheKey::ACTIVE_LANGUAGES,
            CacheKey::DEFAULT_LANGUAGE,
            CacheKey::ALL_LANGUAGES
        ];
        foreach ($keys as $key) {
            $this->invalidateCache($key);
        }
    }

}
