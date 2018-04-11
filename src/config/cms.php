<?php
/**
 * Author: Xavier Au
 * Date: 1/2/2018
 * Time: 1:26 PM
 */

return [
    'theme_directory'        => function (string $theme) {
        return resource_path("views/themes/" . $theme);
    },
    'content_type'           => [],
    'use_spark'              => false,
    'content_cache_duration' => 10,
    'bindings'               => [
        \Anacreation\Cms\Contracts\CmsPageInterface::class => \Anacreation\Cms\Models\Page::class
    ],
];