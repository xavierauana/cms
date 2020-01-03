<?php

use Faker\Generator as Faker;

$factory->define(\Anacreation\MultiAuth\Model\AdminPermission::class,
    function (Faker $faker) {
        return [
            'code'  => $faker->randomLetter,
            'label' => $faker->randomLetter,
        ];
    });
