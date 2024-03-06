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
        Schema::create('students', function (Blueprint $table) {
            //id del estudiante
            $table->id();
            //documento del estudiante
            $table->string('document_type')->default('001');
            //numero de documento del estudiante
            $table->string('document_number');
            //nombre del estudiante
            $table->string('name');
            //apellido del estudiante
            $table->string('paternal_surname');
            //apellido de la madre del estudiante
            $table->string('maternal_surname')->nullable();
            //correo del estudiante
            $table->string('email')->nullable();
            //telefono del estudiante
            $table->string('phone_number')->nullable();
            //estado del estudiante
            $table->boolean('is_enabled')->default(true);
            //agencia que registro al estudiante
            $table->foreignId('agency_id')->constrained('agencies');
            //el document_number y el agency_id deben ser unicos
            $table->unique(['document_number', 'agency_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
