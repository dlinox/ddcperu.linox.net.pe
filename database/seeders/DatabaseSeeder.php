<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Agency;
use App\Models\Course;
use App\Models\Student;
use GuzzleHttp\Promise\Create;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use PHPUnit\Framework\Constraint\Count;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Course::create(
            [
                'code' => '000',
                'name' => 'Manejo preventivo de motocicletas',
                'description' => 'Curso de manejo preventivo de motocicletas',
                'is_enabled' => true,
            ]
        );
        Course::create(
            [
                'code' => '001',
                'name' => 'Manejo preventivo de vehículos',
                'description' => 'Curso de manejo preventivo de vehículos',
                'is_enabled' => true,
            ]
        );

        /*
 protected $fillable = [
        'name',
        'code_nsc',
        'ruc',
        'denomination',
        'email_institutional',
        'phone',
        'license_start',
        'license_end',
        'is_enabled'
    ];
        */

        Agency::create(
            [
                'name' => 'Agencia 1',
                'code_nsc' => '001',
                'ruc' => '123456789',
                'denomination' => 'Agencia 1',
                'email_institutional' => 'a1@gmail.com',
                'phone' => '123456789',
                'license_start' => '2021-01-01',
                'license_end' => '2025-01-01',
                'is_enabled' => true,
            ],
        );
        /*
          protected $fillable = [
        'document_type',
        'document_number',
        'name',
        'paternal_surname',
        'maternal_surname',
        'email',
        'phone_number',
        'agency_id',
        'link',
        'is_enabled'
    ];
        */

        Student::create(
            [
                'document_type' => 'DNI',
                'document_number' => '12345678',
                'name' => 'Juan',
                'paternal_surname' => 'Perez',
                'maternal_surname' => 'Gomez',
                'email' => 'studen@gmail.com',
                'phone_number' => '123456789',
                'agency_id' => 1,
                'link' => 'https://www.google.com',
                'is_enabled' => true,
            ]
        );

        Student::create(
            [
                'document_type' => 'DNI',
                'document_number' => '87654321',
                'name' => 'Maria',
                'paternal_surname' => 'Gomez',
                'maternal_surname' => 'Perez',
                'email' => 's2@gmail.com',
                'phone_number' => '123456789',
                'agency_id' => 1,
                'link' => 'https://www.google.com',
                'is_enabled' => true,
            ]
        );

        // WithoutModelEvents::class;
        $this->call([
            UserSeeder::class,
            PermissionSeeder::class,
            ModelHasPermissionSeeder::class,
        ]);
    }
}
