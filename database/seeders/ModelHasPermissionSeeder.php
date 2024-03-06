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


        //permisos de tipo 001
        $permissions = collect(config('app.permissions'))->where('type', '001')->toArray();
        
        $administrador = User::where('role', '001')->first();

        foreach ($permissions as $permission) {
            $administrador->givePermissionTo($permission['name']);
        }
    }
}
