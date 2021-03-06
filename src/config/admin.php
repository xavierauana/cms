<?php
/**
 * Author: Xavier Au
 * Date: 24/5/2017
 * Time: 1:37 PM
 */

return [
    'route_prefix'   => 'admin',
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
            'name'   => 'name',
            'type'   => 'string',
            'unique' => false,
            'index'  => false,
        ],
        [
            'name'   => 'email',
            'type'   => 'string',
            'unique' => true,
        ]
    ]

];