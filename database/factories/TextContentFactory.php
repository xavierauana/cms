<?php

use Anacreation\Cms\ContentModels\TextContent;
use Faker\Generator as Faker;

$factory->define(TextContent::class, function (Faker $faker) {
    return [
        'content' => $faker->paragraph
    ];
});
