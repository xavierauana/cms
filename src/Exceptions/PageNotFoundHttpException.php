<?php
/**
 * Author: Xavier Au
 * Date: 11/1/2018
 * Time: 11:31 AM
 */

namespace Anacreation\Cms\Exceptions;


use Anacreation\Cms\Models\Language;
use Anacreation\Cms\Models\Page;

class PageNotFoundHttpException extends \Exception
{

    public function render() {

        $page = app()->make(Page::class);
        $notFoundPage = $page->whereUri("404")->first() ?? $page;

        return view('themes.' . config('theme.active') . '.layouts.not_found',
            ['page' => $notFoundPage, 'language' => (new Language)]);
    }
}