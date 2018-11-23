<?php

namespace Anacreation\Cms\Models;

use Anacreation\Cms\Contracts\CacheManageableInterface;
use Anacreation\Cms\Contracts\ContentGroupInterface;
use Anacreation\Cms\Events\LinkDeleted;
use Anacreation\Cms\Events\LinkSaved;
use Anacreation\Cms\Services\ContentService;
use Anacreation\CMS\Services\LanguageService;
use Anacreation\Cms\traits\ContentGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

class Link extends Model
    implements ContentGroupInterface, CacheManageableInterface
{
    use ContentGroup;

    protected $dispatchesEvents = [
        'saved'   => LinkSaved::class,
        'deleted' => LinkDeleted::class,
    ];

    protected $fillable = [
        'is_active',
        'parent_id',
        'page_id',
        'external_uri'
    ];

    protected $appends = [
        'name',
        'uri',
        'absoluteUri'
    ];

    // Relation
    public function menu(): Relation {
        return $this->belongsTo(Menu::class);
    }

    public function children(): Relation {
        return $this->hasMany(Link::class, 'parent_id');
    }

    public function parent(): Relation {
        return $this->belongsTo(Link::class);
    }

    public function page(): Relation {
        return $this->belongsTo(Page::class);
    }

    // Accessor

    public function getUriAttribute(): string {
        return $this->page_id ? optional($this->page)->uri : $this->external_uri;
    }

    public function getAbsoluteUriAttribute(): string {
        return $this->page ? url($this->page->uri) : $this->external_uri;
    }

    public function getNameAttribute(string $langCode = null): string {

        $langCode = $langCode ?? app()->getLocale();
        try {
            $ci = ContentService::getContentIndex($this, 'link', $langCode);

            return $ci->content->show();
        } catch (\Exception $e) {
            $language = (new LanguageService())->getFallbackLanguage($langCode);

            return $this->getNameAttribute($language->code);
        }

    }

    // Helpers

    public function hasChild(): bool {
        return $this->children()->count() > 0;
    }

    public function getChildren(): Collection {
        return $this->children()->active()->orderBy('order')->get();
    }

    // Scope

    public function scopeActive(Builder $query): Builder {
        return $query->whereIsActive(true);
    }

    public function scopeTopLevel(Builder $query): Builder {
        return $query->whereParentId(0);
    }

    public function scopeOrder(Builder $query): Builder {
        return $query->orderBy('order')->orderBy('created_at');
    }

    public function scopeDefault(Builder $query): Builder {
        return $query->active()->topLevel()->order();
    }

    public function getCacheKey(): string {
        return "link_" . $this->id;
    }

    public function getContentCacheKey(
        string $langCode, string $contentIdentifier
    ): string {
        return $this->getCacheKey() . "_" . $langCode . "_" . $contentIdentifier;
    }
}
