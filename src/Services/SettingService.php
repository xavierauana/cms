<?php
/**
 * Author: Xavier Au
 * Date: 23/8/2018
 * Time: 10:42 AM
 */

namespace Anacreation\Cms\Services;


use Illuminate\Support\Facades\DB;

class SettingService
{

    const cacheKeyPrefix = "cms_setting:";

    static function set(string $key, string $value) {

        $cacheKey = self::getCacheKey($key);

        if (DB::table("cms_settings")->whereKey($key)->count()) {
            DB::table("cms_settings")->whereKey($key)->update(compact('value'));

            cache()->forget($cacheKey);
            cache()->forever($cacheKey, $value);
        } else {
            DB::table("cms_settings")->insert(compact('key', 'value'));

            cache()->forever($cacheKey, $value);
        }
    }

    static function get(string $key, string $default = null) {
        $cacheKey = self::getCacheKey($key);
        if (cache()->has($cacheKey)) {
            $value = cache($cacheKey);
        } else {
            $record = DB::table("cms_settings")->whereKey($key)->first();
            if ($record) {
                cache()->forever($cacheKey, $record->value);
            }

            $value = $record ? $record->value : $default;
        }

        return $value;
    }

    /**
     * @param string $key
     * @return string
     */
    private static function getCacheKey(string $key): string {
        $cacheKey = static::cacheKeyPrefix . $key;

        return $cacheKey;
    }
}