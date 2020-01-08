<?php

use Faker\Generator as Faker;

$factory->define(\Anacreation\CmsEmail\Models\Campaign::class,

    function (Faker $faker) {
        $fromEmail = $faker->email;
        $replyEmail = $fromEmail;

        return [
            'title'         => $faker->title,
            'subject'       => $faker->sentence,
            'from_name'     => $faker->name,
            'from_address'  => $fromEmail,
            'reply_address' => $replyEmail,
            'template'      => "new_show",
            'is_scheduled'  => false,
            'has_sent'      => false,
        ];
    });
