<?php
/**
 * Author: Xavier Au
 * Date: 25/12/2019
 * Time: 12:03 PM
 */

namespace Anacreation\Cms\Services;


use Anacreation\Cms\Models\CommonContent;

class CommonContentService
{

    private $contents;

    public function __construct() {
        $this->contents = CommonContent::all();
    }

    public function getContent(string $key, string $default = null, string $languageCode = null) {

        $languageCode = $languageCode ?? app()->getLocale();

        /** @var CommonContent $content */
        $content = $this->contents->first(function(CommonContent $commonContent
        ) use ($key) {
            return $commonContent->key === $key;
        });

        if($content) {
            return $content->getContent(CommonContent::Identifier,
                                        $default ?? '',
                                        $languageCode);
        }


        return $default;
    }

    public function getContentWithoutFallBack(string $key, string $default = null,
        string $languageCode = null
    ) {

        $languageCode = $languageCode ?? app()->getLocale();

        /** @var CommonContent $content */
        $content = $this->contents->first(function(CommonContent $commonContent
        ) use ($key) {
            return $commonContent->key === $key;
        });
        if($content) {
            return $content->getContentWithoutFallBack(CommonContent::Identifier,
                                                       $default ?? '',
                                                       $languageCode);
        }

        return $default;
    }
}