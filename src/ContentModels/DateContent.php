<?php

namespace Anacreation\Cms\ContentModels;

use Anacreation\Cms\traits\ContentType;
use Carbon\Carbon;

class DateContent extends DatetimeContent
{
    use ContentType;

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

        return $this->content->toFormattedDateString();
    }

    public function showBackEnd() {
        return optional($this->content)->format("Y-m-d");
    }

}
