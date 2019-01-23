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

    /**
     * @param string $key
     * @param string $value
     * @throws \Exception
     */
//    public function set(string $key, string $value) {
    //
    //        $cacheKey = $this->getCacheKey($key);
    //
    //        if (DB::table(SettingService::tableName)->whereKey($key)->count()) {
    //            DB::table(SettingService::tableName)->whereKey($key)
    //              ->update(compact('value'));
    //
    //            cache()->forget($cacheKey);
    //            cache()->forever($cacheKey, $value);
    //        } else {
    //            $label = ucwords($key);
    //            DB::table(SettingService::tableName)->insert(compact('label', 'key',
    //                'value'));
    //
    //            cache()->forever($cacheKey, $value);
    //        }
    //    }

    /**
     * @param string      $key
     * @param string|null $default
     * @return \Illuminate\Cache\CacheManager|mixed|string
     * @throws \Exception
     */
    public function get(string $key, string $default = null) {
        $cacheKey = $this->getCacheKey($key);
        if (cache()->has($cacheKey)) {
            $value = cache($cacheKey);
        } else {
            $record = $this->findByKey($key);
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

    /**
     * @return \Illuminate\Cache\CacheManager|mixed
     * @throws \Exception
     */
    public function all() {
        $settings = cache(CacheKey::CMS_SETTINGS);

        if (!$settings) {
            $settings = DB::table(SettingService::tableName)->get();
            cache()->forever(CacheKey::CMS_SETTINGS, $settings);
        }


        return $settings;
    }

    public function update(int $settingId, array $data) {
        $record = $this->find($settingId);
        if ($record) {
            DB::table(SettingService::tableName)->whereId($settingId)
              ->update($data);

            $this->invalidateSpecificCacheKey($record);

            cache()->forget(CacheKey::CMS_SETTINGS);

        }

    }

    public function create(array $data = null): bool {
        $result = DB::table(SettingService::tableName)->insert($data);
        if ($result) {
            cache()->forget(CacheKey::CMS_SETTINGS);
        }

        return $result;

    }

    public function delete(int $settingId) {
        $record = $this->find($settingId);

        if ($record) {

            $this->invalidateSpecificCacheKey($record);

            cache()->forget(CacheKey::CMS_SETTINGS);

            DB::table(SettingService::tableName)
              ->whereId($settingId)
              ->delete();

        }

    }

    public function find(int $settingId) {
        return DB::table(SettingService::tableName)->find($settingId);
    }

    /**
     * @param int   $settingId
     * @param array $data
     * @param       $record
     * @return string
     * @throws \Exception
     */
    private function invalidateSpecificCacheKey($record) {

        $cacheKey = $this->getCacheKey($record->key);

        cache()->forget($cacheKey);

    }

    /**
     * @param string $key
     * @return mixed
     */
    private function findByKey(string $key) {
        $record = DB::table(SettingService::tableName)
                    ->whereKey($key)
                    ->first();

        return $record;
    }
}