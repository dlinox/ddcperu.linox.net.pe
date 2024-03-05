<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;

return [

    'name' => env('APP_NAME', 'Laravel'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),

    'timezone' => 'UTC',

    'locale' => 'en',


    'fallback_locale' => 'en',

    'faker_locale' => 'en_US',

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    'maintenance' => [
        'driver' => 'file',
        // 'store' => 'redis',
    ],



    'providers' => ServiceProvider::defaultProviders()->merge([
        /*
         * Package Service Providers...
         */
        Spatie\Permission\PermissionServiceProvider::class,

        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
    ])->toArray(),

    'aliases' => Facade::defaultAliases()->merge([
        // 'Example' => App\Facades\Example::class,
    ])->toArray(),


    'roles' => [
        '000' => 'SuperAdmin',
        '001' => 'Administrador',
        '002' => 'Operador',
        '003' => 'Instructor',
    ],


    'menu' => [
        [
            'title' => "Dashboard",
            'value' => "dashboard",
            'icon' => "mdi-monitor-dashboard",
            'to' => "/a",
            'can' => null,
            'group' => null,
        ],


        [
            'title' => "Sub Agencias",
            'value' => "agencies",
            'icon' => "mdi-domain",
            'to' => "/a/agencies",
            'can' => 'a.users',
            'group' => null,
        ],


        [
            'title' => "Cursos",
            'value' => "courses",
            'icon' => "mdi-book-open-variant",
            'to' => "/a/courses",
            'can' => 'a.users',
            'group' => null,
        ],
        [
            'title' => "Certificados",
            'value' => "certificates",
            'icon' => "mdi-certificate",
            'to' => "/a/certificates",
            'can' => 'a.users',
            'group' => null,
        ],
        [
            'title' => "Usuarios",
            'value' => "users",
            'icon' => "mdi-account-group",
            'to' => "/a/users",
            'can' => 'a.users',
            'group' => [
                [
                    'title' => "Administradores",
                    'value' => "administrators",
                    'icon' => "mdi-account-group",
                    'to' => "/a/administrators",
                    'can' => 'a.users',
                ],

                [
                    'title' => "Instructores",
                    'value' => "instructors",
                    'icon' => "mdi-account-group",
                    'to' => "/a/instructors",
                    'can' => 'a.users',
                    'group' => null,
                ],

            ]
        ]

    ],


    'permissions' => [
        [
            'name' => 'a.users',
            'menu' => 'Gestion de Usuarios',
        ],

    ],

];
