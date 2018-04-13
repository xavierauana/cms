<?php
/**
 * Author: Xavier Au
 * Date: 10/1/2018
 * Time: 9:32 AM
 */

namespace Anacreation\Cms\traits;


use Anacreation\Cms\Models\ContentIndex;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Cache;

trait ContentGroup
{
    public function contentIndices(): Relation {
        return $this->morphMany(ContentIndex::class, 'group');
    }

    /**
     * @param          $key
     * @param callable $callable
     * @return mixed
     */
    protected function loadFromCache($key, callable $callable) {
        $duration = config("cms.content_cache_duration");
        if (Cache::has($key)) {
            \Debugbar::info("Load content cache:" . $key);

            return Cache::get($key);
        } else {

            $value = $callable();

            Cache::put($key, $value ?? "", $duration);

            return $value;
        }
    }
}