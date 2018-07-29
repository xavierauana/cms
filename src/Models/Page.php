<?php

namespace Anacreation\Cms\Models;

use Anacreation\Cms\Contracts\CacheManageableInterface;
use Anacreation\Cms\Contracts\CmsPageInterface;
use Anacreation\Cms\Contracts\ContentGroupInterface;
use Anacreation\Cms\Contracts\ContentTypeInterface;
use Anacreation\Cms\Services\ContentService;
use Anacreation\Cms\Services\LanguageService;
use Anacreation\Cms\Services\TemplateParser;
use Anacreation\Cms\traits\ContentGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class Page extends Model
    implements ContentGroupInterface, CacheManageableInterface, CmsPageInterface
{
    use ContentGroup;

    protected $fillable = [
        'uri',
        'order',
        'template',
        'is_active',
        'has_children',
        'permission_id',
        'is_restricted',
    ];

    # region Relation
    public function children(): Relation {
        return $this->hasMany(Page::class, 'parent_id');
    }

    public function parent(): Relation {
        return $this->belongsTo(Page::class, 'parent_id');
    }

    public function permission(): Relation {
        return $this->belongsTo(Permission::class);
    }
    #endregion

    # region Helpers
    public function hasChildren(): bool {
        return $this->children()->count() > 0;
    }

    public function showPermission(): string {
        return $this->permission ? $this->permission->label : "Not Specified";
    }
    #endregion

    # region Scope
    public function scopeActive(Builder $query): Builder {
        return $query->whereIsActive(true);
    }

    public function scopeTopLevel(Builder $query): Builder {
        return $query->whereParentId(0);
    }

    public function scopeSorted(Builder $query): Builder {
        return $query->latest()->orderBy('order', 'asc');
    }


    #endregion

    // Get Page content

    // Get Page content




    # region Private functions

    /**
     * @param string      $identifier
     * @param string|null $contentType
     * @param string|null $locale
     * @return mixed
     */
//    private
//    function getContentIndex(
//        string $identifier, string $locale = null
//    ): ?ContentIndex {
//
//        return ContentService::getContentIndex($this, $identifier, $locale);
//    }


    #endregion
    public
    function getCacheKey(): string {
        return "page_" . $this->id;
    }

    public
    function getContentCacheKey(
        string $langCode, string $contentIdentifier
    ): string {
        return $this->getCacheKey() . "_" . $langCode . "_" . $contentIdentifier;
    }

    public function getActivePages(): Collection {

        $query = $this->id ? $this->children() : $this->with('children')
                                                      ->topLevel();

        return $query->active()->get();
    }

    public function removeUrlStartSlash(string $uri = null): string {
        $new_uri = $uri ?? $this->uri;
        if (substr($new_uri, 0, 1) == "/") {
            $new_uri = substr($this->uri, 1);

            return $this->removeUrlStartSlash($new_uri);
        }

        return $new_uri;
    }

    public function getRootParent(): Page {
        $parent = $this->parent;

        return $parent === null ? $this : $parent->getRootParent();
    }
}
