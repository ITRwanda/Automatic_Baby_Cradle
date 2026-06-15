<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('device_activities', function (Blueprint $table) {
            // Link activity -> device
            if (!Schema::hasColumn('device_activities', 'device_id')) {
                $table->foreignId('device_id')->nullable()->after('id');
            }

            // General incident/event info shown in reports
            if (!Schema::hasColumn('device_activities', 'event_type')) {
                $table->string('event_type')->nullable()->after('device_id');
            }

            // If you later want to store sensor payload / raw data
            if (!Schema::hasColumn('device_activities', 'payload')) {
                $table->longText('payload')->nullable()->after('event_type');
            }

            // Indexes
            $table->index('device_id');
            $table->index('event_type');
        });

        Schema::table('device_activities', function (Blueprint $table) {
            // Add FK (ignore if already exists)
            // Laravel doesn't provide a direct hasForeignKey helper across versions,
            // so we keep this simple.
            try {
                $table->foreign('device_id')
                    ->references('id')
                    ->on('devices')
                    ->nullOnDelete();
            } catch (\Throwable $e) {
                // no-op
            }
        });
    }

    public function down(): void
    {
        Schema::table('device_activities', function (Blueprint $table) {
            if (Schema::hasColumn('device_activities', 'device_id')) {
                // Dropping FK by dropping column is usually enough.
                $table->dropColumn('device_id');
            }
            if (Schema::hasColumn('device_activities', 'event_type')) {
                $table->dropColumn('event_type');
            }
            if (Schema::hasColumn('device_activities', 'payload')) {
                $table->dropColumn('payload');
            }
        });
    }
};

