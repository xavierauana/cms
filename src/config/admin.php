<?php
/**
 * Author: Xavier Au
 * Date: 24/5/2017
 * Time: 1:37 PM
 */

return [
    'model_fillable' => [
        'name',
        'email',
        'password',
    ],

    'guards' => [
        'admin' => [
            'driver'   => 'session',
            'provider' => 'admins',
        ],

        'admin-api' => [
            'driver'   => 'token',
            'provider' => 'admins',
        ],
    ],

    'providers' => [
        'admins' => [
            'driver' => 'eloquent',
            'model'  => Anacreation\MultiAuth\Model\Admin::class,
        ],
    ],

    'passwords' => [
        'admins' => [
            'provider' => 'admins',
            'table'    => 'password_resets',
            'expire'   => 15,
        ],
    ],


    // id, password, remember_token, created_at, updated_at are defaults fields
    'table'     => [
        [
            'type'   => 'string',
            'name'   => 'name',
            'unique' => false,
            'index'  => false,
        ],
        [
            'type'   => 'string',
            'name'   => 'email',
            'unique' => true,
        ]
    ]

];