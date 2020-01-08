<?php

use Faker\Generator as Faker;

$factory->define(\Anacreation\CmsEmail\Models\EmailList::class,

    function (Faker $faker) {
        return [
            'title'               => $faker->title,
            'confirm_opt_in'      => false,
            'has_welcome_message' => false,
            'has_goodbye_message' => false,
        ];
    });
