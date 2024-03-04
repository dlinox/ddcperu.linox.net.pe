<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 000:SUPERADMIN
     * 001:ADMINISTRADOR
     * 002:OPERADOR
     * 003:INSTRUCTOR
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['000', '001', '002', '003'])->default('001');
            $table->unsignedBigInteger('agency_id')->nullable();
            $table->unsignedBigInteger('profile_id');
            $table->boolean('is_enabled')->default(true);
            $table->timestamp('email_verified_at')->nullable();
            $table->unique(['role', 'profile_id']);
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
