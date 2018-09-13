<?php

use Anacreation\Cms\Services\SettingService;

function getActiveThemePath(): string {
    return resource_path("views/themes/" . config("cms.active_theme"));
}


function setting(string $key = null, $default) {
    $service = app(SettingService::class);

    if ($key !== null) {
        return $service->get($key, $default);
    }

    return $service;
}