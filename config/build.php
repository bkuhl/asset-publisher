<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Build Path
    |--------------------------------------------------------------------------
    |
    | The relative path to the directory within your git repo you'd
    | like distributed.
    |
    */

    'path' => env('BUILD_PATH', 'build'),

    /*
    |--------------------------------------------------------------------------
    | Security Token
    |--------------------------------------------------------------------------
    |
    | A security token will allow you to only process deploys for
    | authorized requests.
    |
    */

    'token' => env('TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Distribution
    |--------------------------------------------------------------------------
    |
    */

    'distribution' => [

        // Deploy assets to a directory within the distributor.  This
        // is useful when a distributor hosts multiple types of assets
        // or projects.
        // example: https://s3.amazonaws.com/my-bucket/[REPOSITORY-NAME]/v1.0.0/...
        'use_namespaces' => env('USE_NAMESPACES', false),

        'aws' => [
            'bucket' => env('AWS_BUCKET')
        ]
    ],

];
