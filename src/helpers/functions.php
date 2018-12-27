<?php

use Anacreation\Cms\Services\SettingService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

if (!function_exists("getActiveThemePath")) {
    function getActiveThemePath(): string {
        return resource_path("views/themes/" . config("cms.active_theme"));
    }
}
if (!function_exists("getPartialKeyPath")) {
    function getPartialKeyPath($partialName): string {
        return "themes." . config("cms.active_theme") . '.partials.' . $partialName;
    }
}


if (!function_exists("setting")) {

    function setting(string $key = null, $default) {
        $service = app(SettingService::class);

        if ($key !== null) {
            return $service->get($key, $default);
        }

        return $service;
    }
}

if (!function_exists("getAnalyticUrl")) {
    function getAnalyticUrl(string $url, string $source, array $param = null
    ): string {

        /** @var AnalyticUrlBuilderInterface $builder */
        $builder = app(\Anacreation\Cms\Contracts\AnalyticUrlBuilderInterface::class);

        $builder->setUrl($url)
                ->setSource($source);

        $availableKeys = [
            'name',
            'term',
            'medium',
            'content',
        ];

        foreach ($availableKeys as $key) {
            if (in_array($key, array_keys($param))) {
                $word = ucwords($key);
                $method = "set{$word}";
                $builder->$method($param[$key]);
            }
        }

        return $builder->get();

    }
}
if (!function_exists("updateUserSession")) {
    function updateUserSession(Authenticatable $user, Request $request
    ): string {
        $table = "user_sessions";
        if (Schema::hasTable($table)) {
            DB::table($table)->insert([
                'user_id'    => $user->id,
                'session'    => $request->session()->getId(),
                'created_at' => \Carbon\Carbon::now(),
                'updated_at' => \Carbon\Carbon::now(),
            ]);
        }
    }
}
