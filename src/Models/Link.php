<?php

namespace Anacreation\Cms\Models;

use Anacreation\Cms\Contracts\CacheManageableInterface;
use Anacreation\Cms\Contracts\ContentGroupInterface;
use Anacreation\Cms\Enums\LinkMediaCollections;
use Anacreation\Cms\Events\LinkDeleted;
use Anacreation\Cms\Events\LinkSaved;
use Anacreation\Cms\traits\ContentGroup;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Link extends Model
    implements ContentGroupInterface, CacheManageableInterface, HasMedia
{

    use ContentGroup, HasMediaTrait;

    protected $dispatchesEvents = [
        'saved'   => LinkSaved::class,
        'deleted' => LinkDeleted::class,
    ];

    protected $fillable = [
        'external_uri',
        'is_active',
        'parent_id',
        'page_id',
        'order',
        'class',
    ];

    protected $appends = [
        'name',
        'uri',
        'absoluteUri',
    ];

    public const Identifier = "link";

    public function registerMediaCollections() {
        $this->addMediaCollection(LinkMediaCollections::IMAGES);
    }

    // Relation
    public function menu(): Relation {
        return $this->belongsTo(Menu::class);
    }

    public function children(): Relation {
        return $this->hasMany(Link::class,
                              'parent_id');
    }

    public function parent(): Relation {
        return $this->belongsTo(Link::class);
    }

    public function page(): Relation {
        return $this->belongsTo(Page::class);
    }

    // Accessor

    public function getUriAttribute(): ?string {
        return $this->page_id ? optional($this->page)->uri: $this->external_uri;
    }

    public function getAbsoluteUriAttribute(): string {

        return $this->page ? url($this->constructFullUrl($this->page)): $this->external_uri;
    }

    public function getNameAttribute(string $langCode = null): string {

        return $this->getContent(Link::Identifier,
                                 "",
                                 $langCode);

    }

    // Helpers

    public function hasChild(): bool {
        return $this->children()->count() > 0;
    }

    public function getChildren(): Collection {
        return $this->children()->active()->orderBy('order')->get();
    }

    public function addImage(UploadedFile $file, string $languageCode) {

        $this->deleteImage($languageCode);

        $this->addMedia($file->path())
             ->usingName($languageCode)
             ->toMediaCollection(LinkMediaCollections::IMAGES);

    }

    public function getImage(string $languageCode = null): ?string {

        $languageCode = $languageCode ?? app()->getLocale();

        $mediaItems = $this->getMedia(LinkMediaCollections::IMAGES);

        return optional($mediaItems->first(function(Media $item) use (
            $languageCode
        ) {
            return $item->name === $languageCode;
        }))->getFullUrl();
    }

    public function getImagePath(string $languageCode = null): ?string {
        $languageCode = $languageCode ?? app()->getLocale();

        $mediaItems = $this->getMedia(LinkMediaCollections::IMAGES);

        return optional($mediaItems->first(function(Media $item) use (
            $languageCode
        ) {
            return $item->name === $languageCode;
        }))->getPath();
    }

    public function deleteImage(string $languageCode) {
        $mediaItems = $this->getMedia(LinkMediaCollections::IMAGES);
        optional($mediaItems->first(function(Media $item) use (
            $languageCode
        ) {
            return $item->name === $languageCode;
        }))->delete();
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
        return "link_".$this->id;
    }

    public function getContentCacheKey(
        string $langCode, string $contentIdentifier
    ): string {
        return $this->getCacheKey()."_".$langCode."_".Link::Identifier;
    }

    private function constructFullUrl(Page $p): string {
        $uri = $p->parent_id !== 0 ?
            $this->constructFullUrl($p->parent)."/".$p->uri:
            $p->uri;

        return $uri;
    }

    public function getActiveChildren(): void {
        $this->load([
                        'children' => function($query) {
                            $query->where('is_active',
                                          true)
                                  ->orderBy('order');
                        },
                    ]);

        $this->children->each(function($child) {
            $child->getActiveChildren();
        });
    }
}
