<?php

use Faker\Generator as Faker;

$factory->define(Anacreation\Cms\Models\Link::class, function (Faker $faker) {
    return [
        'url'  => $faker->url,
        'name' => $faker->name,
    ];
});
