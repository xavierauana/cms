<?php

namespace Anacreation\Cms\ContentModels;

use Anacreation\Cms\Contracts\ContentTypeInterface;
use Anacreation\Cms\traits\ContentType;
use Carbon\Carbon;

class DateContent extends DatetimeContent implements ContentTypeInterface
{
    use ContentType;

    public function show(array $params = []) {
        $locale = $params['locale'] ?? app()->getLocale();
        $format = $params['$format'] ?? null;

        Carbon::setLocale($locale);

        if ($format) {
            return $this->content->format($format);
        }

        return $this->content->toFormattedDateString();
    }

}
