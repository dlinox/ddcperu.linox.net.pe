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
            //rango de inicio
            $table->integer('range_start');
            //rango de fin
            $table->integer('range_end');
            //cantidad de certificados
            $table->integer('quantity');
            //estado del certificado
            $table->string('is_enabled')->default(true);
            //id de la agencia
            $table->foreignId('agency_id')->constrained('agencies');
            //id del usuario
            $table->foreignId('user_id')->constrained('users');

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
