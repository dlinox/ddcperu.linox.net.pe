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
            'can' => 'a.agencies',
            'group' => null,
        ],
        [
            'title' => "Cursos",
            'value' => "courses",
            'icon' => "mdi-book-open-variant",
            'to' => "/a/courses",
            'can' => 'a.courses',
            'group' => null,
        ],
        [
            'title' => "Estudiantes",
            'value' => "a.students",
            'icon' => "mdi-account-group",
            'to' => "/a/students",
            'can' => 'a.students.admin',
            'group' => null,
        ],
        [
            'title' => "Certificados",
            'value' => "certificates",
            'icon' => "mdi-certificate",
            'to' => "/a/certificates",
            'can' => 'a.certificates',
            'group' => null,
        ],
        [
            'title' => "Reportes de Certificados",
            'value' => "reports",
            'icon' => "mdi-file-chart",
            'to' => "/a/reports",
            'can' => 'a.reports',
            'group' => [
                [
                    'title' => "Sub agencias",
                    'value' => "students",
                    'icon' => "mdi-account-group",
                    'to' => "/a/reports/certificates/agencies",
                    'can' => 'a.reports',
                ],
                [
                    'title' => "Instructores",
                    'value' => "certificates",
                    'icon' => "mdi-certificate",
                    'to' => "/a/reports/certificates/instructors",
                    'can' => 'a.reports',
                ]
            ]
        ],
        [
            'title' => "Usuarios",
            'value' => "users",
            'icon' => "mdi-account-group",
            'to' => "/a/users",
            'can' => 'a.administrators|a.instructors',
            'group' => [
                [
                    'title' => "Administradores",
                    'value' => "administrators",
                    'icon' => "mdi-account-group",
                    'to' => "/a/administrators",
                    'can' => 'a.administrators',
                ],

                [
                    'title' => "Instructores",
                    'value' => "instructors",
                    'icon' => "mdi-account-group",
                    'to' => "/a/instructors",
                    'can' => 'a.instructors',
                    'group' => null,
                ],

            ]
        ],
        [
            'title' => "Instructores",
            'value' => "instructors",
            'icon' => "mdi-account-group",
            'to' => "/s/instructors",
            'can' => 's.instructors',
            'group' => null,
        ],
        [
            'title' => "Certificados",
            'value' => "certificates",
            'icon' => "mdi-certificate",
            'to' => "/s/certificates",
            'can' => 's.certificates',
            'group' => null,
        ],
        [
            'title' => "Estudiantes",
            'value' => "students",
            'icon' => "mdi-account-group",
            'to' => "/s/students",
            'can' => 's.students',
            'group' => null,
        ],
        [
            'title' => "Certificados",
            'value' => "certificates",
            'icon' => "mdi-certificate",
            'to' => "/i/certificates",
            'can' => 'i.certificates',
            'group' => null,
        ]
    ],


    'permissions' => [

        [
            'name' => 'a.agencies',
            'menu' => 'Gestion de Sub agencias',
            'type' => '001'
        ],
        [
            'name' => 'a.courses',
            'menu' => 'Gestion de Cursos',
            'type' => '001'
        ],
        [
            'name' => 'a.certificates',
            'menu' => 'Gestion de Certificados',
            'type' => '001'
        ],

        [
            'name' => 'a.reports',
            'menu' => 'Reportes',
            'type' => '001',
        ],
        [
            'name' => 'a.administrators',
            'menu' => 'Gestion de Administradores',
            'type' => '001'
        ],
        [
            'name' => 'a.students.admin',
            'menu' => 'Gestion de Estudiantes',
            'type' => '001'
        ],
        [
            'name' => 'a.instructors',
            'menu' => 'Gestion de Instructores',
            'type' => '001'
        ],
        [
            'name' => 's.instructors',
            'menu' => 'Gestion de Instructores',
            'type' => '002'

        ],
        [
            'name' => 's.certificates',
            'menu' => 'Gestion de Certificados',
            'type' => '002'
        ],

        [
            'name' => 's.students',
            'menu' => 'Gestion de Estudiantes',
            'type' => '002'
        ],

        [
            'name' => 'i.certificates',
            'menu' => 'Gestion certificados',
            'type' => '003'
        ],

    ],

];
