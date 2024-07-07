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
        Schema::create('student_certificates', function (Blueprint $table) {
            //id 
            $table->id();
            //id course
            $table->foreignId('course_id')->constrained('courses');
            //id del instructor
            $table->foreignId('instructor_id')->constrained('instructors');
            //id del estudiante
            $table->foreignId('student_id')->constrained('students');
            //id del certificado
            $table->foreignId('certificate_id')->constrained('certificate_details');
            //usuario que registro el certificado
            $table->foreignId('user_id')->constrained('users');
            //fecha de vigencia - inicio
            $table->date('start_date');
            //fecha de vigencia - fin
            $table->date('end_date');
            //estado de aprobacion
            $table->boolean('is_approved')->default(false);
            //estado registro
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_certificates');
    }
};
