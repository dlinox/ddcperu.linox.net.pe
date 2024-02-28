<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModelHasPermissionSeeder extends Seeder
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

        $administrador = User::where('role', 'Administrador')->first();

        foreach ($permissions as $permission) {
            $administrador->givePermissionTo($permission['name']);
        }
    }
}
