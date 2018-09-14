<?php

function getActiveThemePath(): string {
    return resource_path("views/themes/" . config("cms.active_theme"));
}


if (!function_exists("getAnalyticUrl")) {
    function getAnalyticUrl(string $url, string $source, array $param = null
    ): string {
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