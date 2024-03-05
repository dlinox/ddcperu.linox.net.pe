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
        Schema::create('certificates', function (Blueprint $table) {
            //id del certificado
            $table->id();
            //id de la agencia
            $table->foreignId('agency_id')->constrained('agencies');
            //id del usuario
            $table->foreignId('user_id')->constrained('users');
            //id del curso
            $table->foreignId('course_id')->constrained('courses');
            //estado del certificado
            $table->string('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
