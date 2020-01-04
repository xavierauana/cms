<?php

namespace Anacreation\Cms\Models;

use Anacreation\Cms\Contracts\CacheManageableInterface;
use Anacreation\Cms\Contracts\ContentGroupInterface;
use Anacreation\Cms\Events\CommonContentDeleted;
use Anacreation\Cms\Events\CommonContentSaved;
use Anacreation\Cms\traits\ContentGroup;
use Anacreation\Cms\traits\SearchableTrait;
use Anacreation\Cms\traits\SortableTrait;
use Illuminate\Database\Eloquent\Model;

class CommonContent extends Model
    implements ContentGroupInterface, CacheManageableInterface
{
    use ContentGroup, SortableTrait, SearchableTrait;

    public const Identifier = 'content';

    protected $dispatchesEvents = [
        'saved'   => CommonContentSaved::class,
        'deleted' => CommonContentDeleted::class,
    ];

    protected $fillable = [
        'label',
        'key',
        'type',
    ];

    protected $sortableColumns = [
        'label',
        'key',
    ];

    protected $searchableColumns = [
        'label',
        'key',
    ];

    //endregion


    // region CmsPageInterface, ContentGroupInterface ,CacheManageableInterface

    public function getCacheKey(): string {
        return 'command_content_'.$this->id;
    }

    public function getContentCacheKey(
        string $langCode, string $contentIdentifier
    ): string {
        return $this->getCacheKey().'_'.$langCode.'_'.$contentIdentifier;
    }

    //endregion
    public function create(array $attributes = []) {
        return parent::create($attributes);
    }


}
