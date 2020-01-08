<?php

use Anacreation\Cms\Models\Page;
use Faker\Generator as Faker;

$factory->define(Page::class, function (Faker $faker) {
    return [
        'uri'          => $faker->uuid,
        'template'     => $faker->word,
        'has_children' => false,
    ];
});
