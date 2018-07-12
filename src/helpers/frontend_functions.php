<?php

use Anacreation\Cms\Models\Page;


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
function getPage(array $segments, array $vars = null): ?array {

    list($pages, $pageRepo) = getActivePages($vars['page']);

    if (isRequestHome($segments, $vars)) {
        $id = $pages[""];

        if ($page = $pageRepo->with('children')->find($id)) {
            return createData($page);
        }

        throw new Exception("No Home Page");

    } elseif ($id = getPageId($segments, $pages)) {

        /** @var Page $page */
        $page = isset($vars['page']) ? $vars['page']->children()
                                                    ->with('children')
                                                    ->find($id) :
            $pageRepo->with('children')->find($id);

        return ($segments = getNextRequestSegments($segments,
            $page)) ? getPage($segments, ['page' => $page]) : createData($page);
    }

    return null;
}

/**
 * @param array $segments
 * @param       $page
 * @return bool
 */
function getNextRequestSegments(array $segments, $page): ?array {
    if ($page and isset($segments[1])) {
        unset($segments[0]);

        return array_values($segments);
    }

    return null;
}

/**
 * @param array $segments
 * @param       $pages
 * @return bool
 */
function getPageId(array $segments, $pages): ?int {
    if (in_array($segments[0], array_keys($pages))) {
        return $pages[$segments[0]];
    }

    return null;
}

/**
 * @param $page
 * @return mixed
 */
function createData($page) {
    $data = $page->injectLayoutModels();
    $data['page'] = $page;
    $data['language'] = new \Anacreation\Cms\Models\Language;

    return $data;
}

/**
 * @param array $segments
 * @param array $vars
 * @return bool
 */
function isRequestHome(array $segments, array $vars = null): bool {
    return !isset($vars['page']) and count($segments) === 0;
}

/**
 * @param \Anacreation\Cms\Models\Page $page
 * @return array
 */
function getActivePages(Page $page = null): array {

    if ($page) {
        $pageRepo = $page;

        $query = $pageRepo->children();

    } else {
        $pageRepo = app()->make(Page::class);

        $query = $pageRepo->topLevel();
    }

    $allActivePageUri = $query->active()
                              ->pluck('id', 'uri')
                              ->toArray();

    $removeSlash = function ($string) {
        return str_replace("/", "", $string);
    };

    $pages = array_combine(
        array_map($removeSlash, array_keys($allActivePageUri)),
        array_values($allActivePageUri));

    return array($pages, $pageRepo);
}

function getPageChildren(Page $page) {
    return $page->children;
}

function getLayoutFiles() {
    $layouts = scandir(getActiveThemePath() . "/layouts");

    return sanitizeFileNames(compact("layouts"));

}

function getPartialFiles() {

    $partials = scandir(getActiveThemePath() . "/partials");

    return sanitizeFileNames(compact("partials"));

}

function getDesignFiles() {
    return [
        'layouts'  => getLayoutFiles()['layouts'],
        'partials' => getPartialFiles()['partials']
    ];
}

function sanitizeFileNames(array $items) {
    foreach ($items as $key => $rawNameArray) {
        $rawNameArray = array_slice($rawNameArray, 2);

        foreach ($rawNameArray as $index => $fileName) {
            $rawNameArray[$index] = str_replace('.blade.php', '',
                $fileName);
        }
        $items[$key] = $rawNameArray;
    }

    return $items;

}