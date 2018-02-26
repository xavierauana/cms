<?php

namespace Anacreation\Cms\ContentModels;

use Anacreation\Cms\Contracts\ContentTypeInterface;
use Anacreation\CMS\Entities\ContentObject;
use Anacreation\Cms\traits\ContentType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DatetimeContent extends Model implements ContentTypeInterface
{
    use ContentType;

    protected $dates = [
        'content'
    ];

    public function updateContent(ContentObject $contentObject
    ): ContentTypeInterface {
        $this->content = new Carbon($contentObject->content);
        $this->save();

        return $this;
    }

    public function saveContent(ContentObject $contentObject
    ): ContentTypeInterface {
        $this->content = new Carbon($contentObject->content);
        $this->save();

        return $this;
    }

    public function show() {
        Carbon::setLocale(app()->getLocale());

        return $this->content->toDayDateTimeString();
    }

    public function deleteContent(array $query = null) {
        $this->content = Carbon::createFromDate(1900, 1, 1);
        $this->save();
    }
}
