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

    protected $table = 'datetime_contents';

    protected $dates = [
        'content',
    ];

    public function updateContent(ContentObject $contentObject
    ): ContentTypeInterface {
        $this->content = $contentObject->content ? new Carbon($contentObject->content): null;
        $this->save();

        return $this;
    }

    public function saveContent(ContentObject $contentObject
    ): ContentTypeInterface {
        $this->content = $contentObject->content ? new Carbon($contentObject->content): null;
        $this->save();

        return $this;
    }

    public function show(array $params = []) {
        if($this->attributes['content'] === null) {
            return null;
        }
        $locale = $params['locale'] ?? app()->getLocale();
        $format = $params['format'] ?? null;

        Carbon::setLocale($locale);

        if($format) {
            return $this->content->format($format);
        }

        return $this->content->toDateTimeString();
    }

    public function showBackEnd() {
        return optional($this->content)->format("Y-m-d h:m");
    }

    public function deleteContent(array $query = null) {
        $this->content = Carbon::createFromDate(1900,
                                                1,
                                                1);
        $this->save();
    }
}
