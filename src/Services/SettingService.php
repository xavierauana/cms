<?php
/**
 * Author: Xavier Au
 * Date: 23/8/2018
 * Time: 10:42 AM
 */

namespace Anacreation\Cms\Services;


use Anacreation\Cms\CacheKey;
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
        $settings = cache(CacheKey::CMS_SETTINGS);

        if (!$settings) {
            $settings = DB::table(SettingService::tableName)->get();
            cache()->forever(CacheKey::CMS_SETTINGS, $settings);
        }


        return $settings;
    }

    public function find(int $settingId) {
        return DB::table(SettingService::tableName)->find($settingId);
    }

    public function update(int $settingId, array $data) {
        DB::table(SettingService::tableName)->whereId($settingId)
          ->update($data);

        cache()->forget(CacheKey::CMS_SETTINGS);
    }

    public function create(array $data = null): bool {
        $result = DB::table(SettingService::tableName)->insert($data);
        if ($result) {
            cache()->forget(CacheKey::CMS_SETTINGS);
        }

        return $result;

    }

    public function delete(int $settingId) {
        $record = DB::table(SettingService::tableName)
                    ->whereId($settingId)
                    ->first();

        if ($record) {

            $cacheKey = $this->getCacheKey($record->key);

            cache()->forget($cacheKey);

            cache()->forget(CacheKey::CMS_SETTINGS);

            DB::table(SettingService::tableName)
              ->whereId($settingId)
              ->delete();

        }

    }
}