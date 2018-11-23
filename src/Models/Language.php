<?php

namespace Anacreation\Cms\Models;

use Anacreation\Cms\Contracts\CacheManageableInterface;
use Anacreation\Cms\Events\LanguageDeleted;
use Anacreation\Cms\Events\LanguageSaved;
use Anacreation\CMS\Services\LanguageService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

class Language extends Model implements CacheManageableInterface
{
    protected $fillable = [
        'label',
        'code',
        'is_active',
        'is_default',
        'fallback_language_id'
    ];

    protected $dispatchesEvents = [
        'saved'   => LanguageSaved::class,
        'deleted' => LanguageDeleted::class,
    ];

    // Relation
    public function fallbackLanguage(): Relation {
        return $this->belongsTo(Language::class, 'fallback_language_id');
    }

    // Scope
    public function scopeActive(Builder $query): Builder {
        return $query->whereIsActive(true);
    }

    // Accessor
    public function getDefaultLanguageAttribute(): Language {
        $langService = new LanguageService();

        return $langService->defaultLanguage;
    }

    public function getActiveLanguagesAttribute(): Collection {

        return $this->activeLanguages();
    }

    public function getFallbackLanguageAttribute(): Language {
        return $this->fallbackLanguage ?? $this->defaultLanguage;
    }

    // Helpers
    
    public function getCacheKey(): string {
        return "language_" . $this->id;
    }

    public function activeLanguages(): Collection {
        $langService = new LanguageService();

        return $langService->activeLanguages;
    }
}
