<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 001:DNI
     * 002:RUC
     * 003:CE
     * 004:PASS
     * 005:PTP
     */
    public function up(): void
    {
        Schema::create('administrators', function (Blueprint $table) {
            $table->id();
            $table->char('document_type', 3)->default('001');
            $table->char('document_number', 12)->unique();
            $table->string('name', 100);
            $table->string('last_name', 180);
            $table->char('phone_number', 15);
            $table->boolean('is_sub_admin')->default(false);
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('administrators');
    }
};
