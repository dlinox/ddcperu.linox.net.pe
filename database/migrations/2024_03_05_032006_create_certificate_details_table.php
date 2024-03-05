<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * status: 
     * 000: disponible
     * 001: pendiente
     * 002: asignado
     * 003: vencido
     */
    public function up(): void
    {
        Schema::create('certificate_details', function (Blueprint $table) {
            //id del detalle del certificado
            $table->id();
            //id del certificado
            $table->foreignId('certificate_id')->constrained('certificates');
            //id curso
            $table->foreignId('course_id')->constrained('courses');
            //numero del certificado
            $table->char('number', 20);
            //estado del certificado
            $table->char('status', 3)->default('000');

            $table->unique(['course_id', 'number']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificate_details');
    }
};
