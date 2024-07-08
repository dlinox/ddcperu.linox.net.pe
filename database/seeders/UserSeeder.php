<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use function Laravel\Prompts\password;

class UserSeeder extends Seeder
{

    public function run(): void
    {

        $admin = [
            'document_number' => '11111111',
            'name' => 'Admin',
            'last_name' => 'Admin',
            'phone_number' => '1111111111',
        ];

        $users = [
            [
                'username' => 'admin',
                'email' => 'admin@test.com',
                'password' => 'password',
                'role' => '001',
                'agency_id' => null,
                'profile_id' => \App\Models\Administrator::create($admin)->id,

            ],

        ];

        foreach ($users as $user) {
            \App\Models\User::create($user);
        }
    }
}
