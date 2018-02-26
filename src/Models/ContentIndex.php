<?php

namespace Anacreation\Cms\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

class ContentIndex extends Model
{
    protected $fillable = [
        'content_type',
        'content_id',
        'lang_id',
        'identifier',
    ];

    public function content(): Relation {
        return $this->morphTo();
    }

    public function contentOwner(): Relation {
        return $this->morphTo();
    }

    public function group(): Relation {
        return $this->morphTo();
    }

    public function scopePage(): Builder {
        return $this->whereGroupType(Page::class);
    }

    public function getPageAttribute(): ?Page {
        if ($this->group_type === Page::class) {
            return app()->make(Page::class)->find($this->group_id);
        }

        return null;
    }

    public function scopeFetchIndex(
        Builder $query, string $identifier, int $langId
    ): Builder {
        return $query->whereIdentifier($identifier)
                     ->whereLangId($langId)
                     ->orderBy('created_at')
                     ->orderBy('order');
    }

}
