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
            $table->id();
            $table->char('number', 20)->unique();
            $table->char('status', 3)->default('000');
            $table->foreignId('certificate_id')->constrained('certificates');
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
