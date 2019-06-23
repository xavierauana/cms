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

    const cacheKeyPrefix = "cms_setting_";
    const tableName      = "cms_settings";

    /**
     * @param string      $key
     * @param string|null $default
     * @return \Illuminate\Cache\CacheManager|mixed|string
     * @throws \Exception
     */
    public function get(string $key, string $default = null) {
        $cacheKey = $this->getCacheKey($key);

        return ($this->all())[$cacheKey] ?? $default;
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
        return cache()->rememberForever(CacheKey::CMS_SETTINGS, function () {
            return DB::table(SettingService::tableName)->get()
                     ->reduce(function ($carry, $record) {
                         $carry[$this->getCacheKey($record->key)] = $record->value;

                         return $carry;
                     }, []);
        });
    }

    public function update(int $settingId, array $data) {
        $record = $this->find($settingId);
        if ($record) {
            DB::table(SettingService::tableName)->whereId($settingId)
              ->update($data);

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

        if ($record = $this->find($settingId)) {

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
}