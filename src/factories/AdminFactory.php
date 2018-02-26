<?php

use Faker\Generator as Faker;

$factory->define(\Anacreation\MultiAuth\Model\Admin::class,
    function (Faker $faker) {
        return [
            'name'     => $faker->name,
            'email'    => $faker->email,
            'password' => bcrypt("123456")
        ];
    });
