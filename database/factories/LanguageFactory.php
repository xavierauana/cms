<?php

use Anacreation\Cms\Models\Language;
use Faker\Generator as Faker;

$factory->define(Language::class,
    function(Faker $faker) {
        return [
            'label'      => $faker->name,
            'code'       => $faker->unique()->languageCode,
            'is_default' => false,
        ];
    });
