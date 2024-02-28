<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $permissions = [
            [
                'name' => 'a.users',
                'menu' => 'Gestion de Usuarios',
            ],
        ];

        foreach ($permissions as $permission) {

            //agisnar todos los permisos al usuario administrador con laravel spatie
            Permission::create($permission);
        }
    }
}
