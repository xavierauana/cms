<?php
/**
 * Author: Xavier Au
 * Date: 1/2/2018
 * Time: 1:26 PM
 */

return [
    'active_theme'              => 'default',
    'theme_directory'           => 'views/themes', // in resources directory
    'use_spark'                 => false,
    'content_cache_duration'    => 10,
    'enable_scheduler'          => true,
    'bindings'                  => [
        \Anacreation\Cms\Contracts\CmsPageInterface::class            => \Anacreation\Cms\Models\Page::class,
        \Anacreation\Cms\Contracts\AnalyticUrlBuilderInterface::class => \Anacreation\GAUrlBuilder\Builder::class,
    ],
    'single_login_session'      => false,
    'content_type'              => [],
    'view_composer'             => [
        'view' => "Class"
    ],
    'translation_table'         => 'translations',
    'custom_redirect'           => [
        //from_uri => to_uri
    ],
    "sitemap_exlcuded_template" => [],
    "sitemap_file_name"         => env("SITEMAP_FILENAME", 'sitemap.xml'),
    'reload_php_fpm'            => env("RELOAD_PHP_FPM_VERSION", null)
];