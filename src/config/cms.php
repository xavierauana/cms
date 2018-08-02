<?php
/**
 * Author: Xavier Au
 * Date: 1/2/2018
 * Time: 1:26 PM
 */

return [
    'active_theme'           => 'default',
    'theme_directory'        => 'views/themes', // in resources directory
    'use_spark'              => false,
    'content_cache_duration' => 10,
    'enable_scheduler'       => true,
    'bindings'               => [
        \Anacreation\Cms\Contracts\CmsPageInterface::class => \Anacreation\Cms\Models\Page::class
    ],
    'single_login_session'   => false,
    /**
     * 'content_type'   =>  [
     * 'encoded_video' => [
     * 'class'     => \App\NewType::class,
     * 'component' => "EncodedVideoContentBlock"
     * ]
     * ]
     * content_type array keys are the default content types listed in
     * _definition.dtd
     * Class is the php class responsible for handling backend
     * interaction Component is the vue front end component
     */
    'content_type'           => [],
];