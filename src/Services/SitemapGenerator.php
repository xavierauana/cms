<?php
/**
 * Author: Xavier Au
 * Date: 2019-07-12
 * Time: 19:48
 */

namespace Anacreation\Cms\Services;


use Anacreation\Cms\Models\Language;
use Anacreation\Cms\Models\Page;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

class SitemapGenerator
{
    public static function make() {
        (new static)->generate();
    }

    public function generate(): Sitemap {

        $generator = Sitemap::create();

        $languages = Language::active()->whereIsDefault(false)
                             ->get();

        $addToGenerator = function (Page $page) use (
            &$generator, $languages
        ) {
            $url = $page->getAbsoluteUrl();

            $u = Url::create($url)->setPriority(0.8);

            $languages->each(function (Language $language) use (
                $url, &$u
            ) {
                $u->addAlternate($url . "?locale=" . $language->code,
                    $language->code);
            });

            $generator->add($u);
        };

        $addToGenerator(Page::whereUri("/")->first());

        Page::active()
            ->where('uri', '<>', '/')
            ->whereInSitemap(true)
            ->where(function ($q) {
                if ($templates = config('cms.sitemap_exlcuded_template')) {
                    collect($templates)->each(function (
                        string $template, int $index
                    ) use (&$q) {
                        $q->where('template', "<>", $template);
                    });
                }

                return $q;
            })
            ->get()
            ->each($addToGenerator);

        $filename = config('cms.sitemap_file_name');

        $generator->writeToFile(public_path($filename));

        return $generator;
    }
}