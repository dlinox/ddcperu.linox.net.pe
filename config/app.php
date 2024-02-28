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

    'permissions' => [
        [
            'name' => 'a.users',
            'menu' => 'Gestion de Usuarios',
        ],

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
            'title' => "Usuarios",
            'value' => "users",
            'icon' => "mdi-account-group",
            'to' => "/a/users",
            'can' => 'a.users',
            'group' => null,
        ],
        [
            'title' => "Agencias",
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
        ]

    ],

];
