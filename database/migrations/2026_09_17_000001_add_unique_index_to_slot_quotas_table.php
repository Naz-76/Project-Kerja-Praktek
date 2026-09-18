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
        Schema::table('slot_quotas', function (Blueprint $table) {
            $table->unique(['department_id', 'period'], 'slot_quotas_dept_period_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('slot_quotas', function (Blueprint $table) {
            $table->dropUnique('slot_quotas_dept_period_unique');
        });
    }
};
