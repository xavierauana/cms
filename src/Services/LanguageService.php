<?php
/**
 * Author: Xavier Au
 * Date: 11/2/2018
 * Time: 10:32 PM
 */

namespace Anacreation\Cms\Services;


use Anacreation\Cms\CacheKey;
use Anacreation\Cms\Models\Language;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cache;

class LanguageService
{
    public $languages;
    public $activeLanguages;
    public $defaultLanguage;


    /**
     * LanguageService constructor.
     */
    public function __construct() {
        $this->languages = Cache::has(CacheKey::ALL_LANGUAGES) ? Cache::get(CacheKey::ALL_LANGUAGES) : Cache::rememberForever(CacheKey::ALL_LANGUAGES,
            function () {
                return Language::with('fallbackLanguage')->get();
            });
        $this->activeLanguages = Cache::has(CacheKey::ACTIVE_LANGUAGES) ? Cache::get(CacheKey::ACTIVE_LANGUAGES) : Cache::rememberForever(CacheKey::ACTIVE_LANGUAGES,
            function () {
                return Language::with('fallbackLanguage')->active()->get();
            });
        $this->defaultLanguage = Cache::has(CacheKey::DEFAULT_LANGUAGE) ? Cache::get(CacheKey::DEFAULT_LANGUAGE) : Cache::rememberForever(CacheKey::DEFAULT_LANGUAGE,
            function () {
                return Language::with('fallbackLanguage')->whereIsDefault(true)
                               ->firstOrFail();
            });
    }

    public function getLanguage(string $locale): Language {

        $language = $this->activeLanguages->first(function (Language $language
        ) use (
            $locale
        ) {
            return $language->code === $locale;
        });

        if ($language) {
            return $language;
        }
        throw new ModelNotFoundException("Cannot find active language with code: " . $locale);
    }

    public function getLanguageById(int $id): Language {

        $language = $this->languages->first(function (Language $language) use (
            $id
        ) {
            return $language->id === $id;
        });
        if ($language === null) {
            throw new ModelNotFoundException("Cannot find language with id:" . $id);
        }

        return $language;

    }

    public function getFallbackLanguage(string $languageCode): Language {

        return $this->getLanguage($languageCode)->fallbackLanguage;
    }

    public function getDefaultLanguage(): Language {
        return $this->defaultLanguage;
    }
}