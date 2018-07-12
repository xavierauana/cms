<?php
/**
 * Author: Xavier Au
 * Date: 1/2/2018
 * Time: 1:26 PM
 */

return [
    'active_theme'           => 'default',
    'theme_directory'        => 'views/themes', // in resources directory
    'content_type'           => [],
    'use_spark'              => false,
    'content_cache_duration' => 10,
    'bindings'               => [
        \Anacreation\Cms\Contracts\CmsPageInterface::class => \Anacreation\Cms\Models\Page::class
    ],
    'single_login_session'   => false
];