<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained('registrations')->onDelete('cascade');
            $table->string('full_name');
            $table->string('nis_nim');
            $table->string('institution_level'); // SMA/SMK, D3, S1, dll.
            $table->string('major'); // Jurusan/Prodi
            $table->string('semester_or_grade');
            $table->string('phone');
            $table->string('email');
            $table->boolean('is_leader')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_participants');
    }
};
