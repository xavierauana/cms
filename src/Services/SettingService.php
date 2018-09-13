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
    const tableName      = "cms_settings";

    /**
     * SettingService constructor.
     */
    public function __construct() {

    }

    public function set(string $key, string $value) {

        $cacheKey = $this->getCacheKey($key);

        if (DB::table(SettingService::tableName)->whereKey($key)->count()) {
            DB::table(SettingService::tableName)->whereKey($key)
              ->update(compact('value'));

            cache()->forget($cacheKey);
            cache()->forever($cacheKey, $value);
        } else {
            $label = ucwords($key);
            DB::table(SettingService::tableName)->insert(compact('label', 'key',
                'value'));

            cache()->forever($cacheKey, $value);
        }
    }

    public function get(string $key, string $default = null) {
        $cacheKey = $this->getCacheKey($key);
        if (cache()->has($cacheKey)) {
            $value = cache($cacheKey);
        } else {
            $record = DB::table(SettingService::tableName)->whereKey($key)
                        ->first();
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
    private function getCacheKey(string $key): string {
        $cacheKey = SettingService::cacheKeyPrefix . $key;

        return $cacheKey;
    }

    public function all() {

    }
}