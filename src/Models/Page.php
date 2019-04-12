<?php

namespace Anacreation\Cms\Models;

use Anacreation\Cms\CacheKey;
use Anacreation\Cms\Contracts\CacheManageableInterface;
use Anacreation\Cms\Contracts\CmsPageInterface;
use Anacreation\Cms\Contracts\ContentGroupInterface;
use Anacreation\Cms\Events\PageDeleted;
use Anacreation\Cms\Events\PageSaved;
use Anacreation\Cms\traits\ContentGroup;
use Anacreation\Cms\traits\SearchableTrait;
use Anacreation\Cms\traits\SortableTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;

class Page extends Model
    implements ContentGroupInterface, CacheManageableInterface, CmsPageInterface
{
    use ContentGroup, SortableTrait, SearchableTrait;

    protected $dispatchesEvents = [
        'saved'   => PageSaved::class,
        'deleted' => PageDeleted::class,
    ];

    protected $fillable = [
        'uri',
        'order',
        'template',
        'is_active',
        'has_children',
        'permission_id',
        'is_restricted',
    ];
    protected $casts = [
        'is_active'     => 'Boolean',
        'has_children'  => 'Boolean',
        'is_restricted' => 'Boolean',
    ];

    protected $sortableColumns = [
        'uri',
        'template',
        'is_active',
    ];

    protected $searchableColumns = [
        'uri',
        'template',
    ];

    // region Relation

    public function children(): Relation {
        return $this->hasMany(Page::class, 'parent_id');
    }

    public function parent(): Relation {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function permission(): Relation {
        return $this->belongsTo(Permission::class);
    }

    //endregion

    // region Helpers

    public function hasChildren(): bool {
        return $this->children()->count() > 0;
    }

    public function showPermission(): string {
        return $this->permission ?
            $this->permission->label :
            'Not Specified';
    }

    public function getAbsoluteUrl(): string {
        if ($parent = $this->parent) {

            $parentUrl = $parent->getAbsoluteUrl();

            return $parentUrl . "/{$this->uri}";
        }

        $segment = request()->segments()[0] ?? "/";

        return url(($segment === 'api' ? "api/" : '/') . ($this->uri !== '/' ? $this->uri : ""));

    }

    //endregion

    // region Scope

    public function scopeActive(Builder $query): Builder {
        return $query->whereIsActive(true);
    }

    public function scopeTopLevel(Builder $query): Builder {
        return $query->whereParentId(0);
    }

    public function scopeSorted(Builder $query): Builder {
        return $query->orderBy('order', 'asc')->latest();
    }

    //endregion

    // region CmsPageInterface, ContentGroupInterface ,CacheManageableInterface

    public function getCacheKey(): string {
        return 'page_' . $this->id;
    }

    public function getContentCacheKey(
        string $langCode, string $contentIdentifier
    ): string {
        return $this->getCacheKey() . '_' . $langCode . '_' . $contentIdentifier;
    }

    public function getActivePages(): Collection {

        if ($this->id) {
            $pages = cache($this->getCacheKey());

            if ($pages) {
                return $pages;
            }

            $pages = $this->children()->sorted()->get();

            cache()->forever($this->getCacheKey(), $pages);

            return $pages;
        } else {
            $pages = cache(CacheKey::TOP_LEVEL_ACTIVE_PAGES);

            if ($pages) {
                return $pages;
            }

            $pages = $this->with('children')->topLevel()->sorted()->get();

            cache()->forever(CacheKey::TOP_LEVEL_ACTIVE_PAGES, $pages);

            return $pages;
        }
    }

    public function removeUrlStartSlash(string $uri = null): string {
        $new_uri = $uri ?? $this->uri;
        if (substr($new_uri, 0, 1) === '/') {
            $new_uri = substr($this->uri, 1);

            return $this->removeUrlStartSlash($new_uri);
        }

        return $new_uri;
    }

    public function getRootParent(): Page {
        $parent = $this->parent;

        return $parent === null ? $this : $parent->getRootParent();
    }

    //endregion
}
