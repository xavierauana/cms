<?php

use Anacreation\Cms\Models\NumberContent;
use Faker\Generator as Faker;

$factory->define(NumberContent::class, function (Faker $faker) {
    return [
        'content' => rand(0, 1) * 100
    ];
});
