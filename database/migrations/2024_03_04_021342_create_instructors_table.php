<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('instructors', function (Blueprint $table) {
            //id de la tabla
            $table->id();
            //id del instructor
            $table->char('instructor_id', 10)->unique();
            //tipo de documento de identidad del instructor
            $table->char('document_type', 3)->default('000');
            //documento de identidad del instructor
            $table->char('document_number', 14)->unique();
            //nombre del instructor
            $table->string('name', 100);
            //apellidos del instructor (paterno y materno)
            $table->string('last_name', 180);
            //correo del instructor
            $table->string('email', 100)->unique();
            //telefono del instructor
            $table->char('phone', 15);
            //fecha de inicio de la licencia
            $table->date('license_start');
            //fecha de fin de la licencia
            $table->date('license_end');
            //agencia a la que pertenece el instructor  
            $table->foreignId('agency_id')->constrained('agencies');
            //estado del instructor (habilitado o deshabilitado)
            $table->boolean('is_enabled')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructors');
    }
};
