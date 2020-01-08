<?php

use Anacreation\Cms\Models\Permission;
use Faker\Generator as Faker;

$factory->define(Permission::class, function (Faker $faker) {
    return [
        'label' => $faker->word,
        'code'  => $faker->uuid,
    ];
});
