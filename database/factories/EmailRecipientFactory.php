<?php

use Faker\Generator as Faker;

$factory->define(\Anacreation\CmsEmail\Models\Recipient::class,

    function (Faker $faker) {
        return [
            'name'  => $faker->name,
            'email' => $faker->unique()->email,
        ];
    });
