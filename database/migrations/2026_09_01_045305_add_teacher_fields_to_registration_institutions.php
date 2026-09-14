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
        Schema::table('registration_institutions', function (Blueprint $table) {
            $table->string('teacher_name')->nullable();
            $table->string('teacher_email')->nullable();
            $table->string('teacher_phone')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_institutions', function (Blueprint $table) {
            $table->dropColumn(['teacher_name', 'teacher_email', 'teacher_phone']);
        });
    }
};
