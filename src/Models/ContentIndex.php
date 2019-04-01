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

    public function scopeFetchIndex(
        Builder $q, string $identifier, int $languageId
    ): Builder {
        return $q->whereIdentifier($identifier)
                 ->whereLangId($languageId);
    }

    public function content(): Relation {
        return $this->morphTo();
    }
}
