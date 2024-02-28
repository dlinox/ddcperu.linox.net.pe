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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 60);
            $table->string('paternal_surname', 60);
            $table->string('maternal_surname', 60);
            $table->char('phone_number', 9);
            $table->char('document_number', 8)->unique();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->enum('role', ['Super Admin', 'Administrador', 'Operador', 'Instructor'])->default('Administrador');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('agency_id')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
