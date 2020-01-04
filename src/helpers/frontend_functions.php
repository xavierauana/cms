<?php

use Anacreation\Cms\Models\Page;
use Anacreation\Cms\Services\SettingService;


/**
 * Author: Xavier Au
 * Date: 10/1/2018
 * Time: 8:43 AM
 * @param string $identifier
 * @param string $slug
 */


/**
 * @param array      $segments
 * @param array|null $vars
 * @return array|null
 * @throws \Exception
 */
if( !function_exists('getPage')) {
    function getPage(array $segments, array $vars = null): ?array {

        list($pages, $pageRepo) = getActivePages($vars['page']);

        if(isRequestHome($segments,
                         $vars)) {
            $id = $pages[""];

            if($page = $pageRepo->with('children')->find($id)) {
                return createData($page);
            }

            throw new Exception("No Home Page");

        } elseif($id = getPageId($segments,
                                 $pages)) {

            /** @var Page $page */
            $page = isset($vars['page']) ? $vars['page']->children()
                                                        ->with('children')
                                                        ->find($id):
                $pageRepo->with('children')->find($id);

            return ($segments = getNextRequestSegments($segments,
                                                       $page)) ? getPage($segments,
                                                                         ['page' => $page]):
                createData($page);
        }

        return null;
    }
}

/**
 * @param array $segments
 * @param       $page
 * @return bool
 */
if( !function_exists('getNextRequestSegments')) {
    function getNextRequestSegments(array $segments, $page): ?array {
        if($page and isset($segments[1])) {
            unset($segments[0]);

            return array_values($segments);
        }

        return null;
    }
}

/**
 * @param array $segments
 * @param       $pages
 * @return bool
 */
if( !function_exists('getPageId')) {
    function getPageId(array $segments, $pages): ?int {
        if(in_array($segments[0],
                    array_keys($pages))) {
            return $pages[$segments[0]];
        }

        return null;
    }
}

/**
 * @param $page
 * @return mixed
 */
if( !function_exists('createData')) {
    function createData($page) {
        $data = $page->injectLayoutModels();
        $data['page'] = $page;
        $data['language'] = new \Anacreation\Cms\Models\Language;

        return $data;
    }
}

/**
 * @param array $segments
 * @param array $vars
 * @return bool
 */
if( !function_exists('isRequestHome')) {
    function isRequestHome(array $segments, array $vars = null): bool {
        return !isset($vars['page']) and count($segments) === 0;
    }
}

/**
 * @param \Anacreation\Cms\Models\Page $page
 * @return array
 */
if( !function_exists('getActivePages')) {
    function getActivePages(Page $page = null): array {

        if($page) {
            $pageRepo = $page;

            $query = $pageRepo->children();

        } else {
            $pageRepo = app()->make(Page::class);

            $query = $pageRepo->topLevel();
        }

        $allActivePageUri = $query->active()
                                  ->pluck('id',
                                          'uri')
                                  ->toArray();

        $removeSlash = function($string) {
            return str_replace("/",
                               "",
                               $string);
        };

        $pages = array_combine(
            array_map($removeSlash,
                      array_keys($allActivePageUri)),
            array_values($allActivePageUri));

        return [$pages, $pageRepo];
    }
}

if( !function_exists('getPageChildren')) {
    function getPageChildren(Page $page) {
        return $page->children;
    }
}

if( !function_exists('getLayoutFiles')) {
    function getLayoutFiles() {
        $layouts = scandir(getActiveThemePath()."/layouts");

        return sanitizeFileNames(compact("layouts"));

    }
}

if( !function_exists('getPartialFiles')) {
    function getPartialFiles() {

        $partials = scandir(getActiveThemePath()."/partials");

        return sanitizeFileNames(compact("partials"));

    }
}
if( !function_exists('getPartialFile')) {
    function getPartialFile($partialFileName) {

        $partials = scandir(getActiveThemePath()."/partials");

        return sanitizeFileNames(compact("partials"));

    }
}

if( !function_exists('getDesignFiles')) {
    function getDesignFiles(): array {
        return [
            'layouts'     => getLayoutFiles()['layouts'],
            'partials'    => getPartialFiles()['partials'],
            'definitions' => getDefinitionFiles()['definitions'],
        ];
    }
}
if( !function_exists('getDefinitionFiles')) {
    function getDefinitionFiles() {
        $definitions = scandir(getActiveThemePath()."/definition");

        return sanitizeFileNames(compact("definitions"));
    }
}

if( !function_exists('sanitizeFileNames')) {
    function sanitizeFileNames(array $items, array $excludeExtension = []) {
        foreach($items as $key => $rawNameArray) {
            $rawNameArray = array_slice($rawNameArray,
                                        2);

            foreach($rawNameArray as $index => $fileName) {
                $rawNameArray[$index] = str_replace('.blade.php',
                                                    '',
                                                    $fileName);
            }
            $items[$key] = $rawNameArray;
        }

        return $items;

    }
}


if( !function_exists('settings')) {
    function settings(string $key) {

        $settings = (New SettingService)->all();

        return $settings[$key] ?? null;
    }
}



