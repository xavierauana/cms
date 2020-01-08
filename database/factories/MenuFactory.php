<?php

use Anacreation\Cms\Models\Menu;
use Faker\Generator as Faker;

$factory->define(Menu::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'code' => $faker->uuid
    ];
});
