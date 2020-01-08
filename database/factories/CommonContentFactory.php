<?php

use Faker\Generator as Faker;

$factory->define(\Anacreation\Cms\Models\CommonContent::class,

    function(Faker $faker) {
        return [
            'label' => $faker->word,
            'type'  => false,
            'key'   => $faker->uuid,
        ];
    });
