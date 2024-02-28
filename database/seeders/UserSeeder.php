<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $users = [
            [
                'name' => 'Admin',
                'paternal_surname' => 'Admin',
                'maternal_surname' => 'Admin',
                'document_number' => '12345678',
                'phone_number' => '123456789',
                'username' => 'admin@ddcperu.com.pe',
                'role' => 'Administrador',
                'email' => 'admin@ddcperu.com.pe',
                'password' => 'password',
            ],
        ];


        foreach ($users as $user) {
            \App\Models\User::create($user);
        }
    }
}
