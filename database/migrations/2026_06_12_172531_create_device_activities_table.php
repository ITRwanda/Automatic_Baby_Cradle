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
        // Table already created by the earlier 2026_06_12_134639 migration.
        // This file is a duplicate — kept only to preserve migration history.
        if (!Schema::hasTable('device_activities')) {
            Schema::create('device_activities', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Intentionally left as no-op to avoid dropping a shared table.
    }
};
