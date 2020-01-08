<?php

use Faker\Generator as Faker;

$factory->define(\Anacreation\MultiAuth\Model\AdminRole::class,
    function (Faker $faker) {
        return [
            'code'  => $faker->randomLetter,
            'label' => $faker->randomLetter,
        ];
    });
