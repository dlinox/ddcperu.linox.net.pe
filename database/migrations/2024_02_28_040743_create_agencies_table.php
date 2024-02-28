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
        Schema::create('agencies', function (Blueprint $table) {
            // id de la agencia
            $table->id();
            // nombre de la agencia
            $table->string('name');
            // codigo NSC de la agencia
            $table->string('code');
            // ruc de la agencia
            $table->char('ruc', 11)->unique();
            // denominacion de la agencia
            $table->string('denomination');
            //correo institucional de la agencia
            $table->string('email')->unique();
            //numero de telefono de la agencia
            $table->string('phone');
            //fecha de inicio de la licencia
            $table->date('license_start');
            //fecha de fin de la licencia
            $table->date('license_end');
            //direccion de la agencia
            $table->string('address');
            //pais de la agencia
            $table->string('country');
            //ciudad de la agencia
            $table->string('city')->nullable();
            //descripcion de la agencia
            $table->char('ubigeo', 6)->default('000000');
            //status de la agencia
            $table->boolean('status')->default(true);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agencies');
    }
};
