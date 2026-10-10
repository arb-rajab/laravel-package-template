<?php

declare(strict_types=1);

return [

    /*
    |--------------------------------------------------------------------------
    | Enabled
    |--------------------------------------------------------------------------
    |
    | Master switch for the package's behaviour. Host applications can turn it
    | off per environment without removing the service provider.
    |
    */
    'enabled' => env('PACKAGE_NAME_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Table name
    |--------------------------------------------------------------------------
    |
    | Override if the default collides with a table in the host application.
    | Set this before running the published migration.
    |
    */
    'table' => env('PACKAGE_NAME_TABLE', 'package_name_examples'),

];
