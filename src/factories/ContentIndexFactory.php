<?php

use Anacreation\Cms\Models\ContentIndex;
use Faker\Generator as Faker;

$factory->define(ContentIndex::class, function (Faker $faker) {
    return [
        'language_id' => 1,
        'identifier'  => str_random(5),
    ];
});
