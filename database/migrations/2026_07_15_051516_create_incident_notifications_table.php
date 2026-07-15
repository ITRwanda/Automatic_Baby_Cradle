<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('incident_notifications', function (Blueprint $table) {
            $table->id();

            // Who receives this notification
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // The activity that triggered it
            $table->foreignId('device_activity_id')
                  ->nullable()
                  ->constrained('device_activities')
                  ->nullOnDelete();

            // The device it came from (denormalised for fast queries)
            $table->foreignId('device_id')
                  ->nullable()
                  ->constrained('devices')
                  ->nullOnDelete();

            // e.g. cry_detected / dht / cradle
            $table->string('event_type', 100)->nullable();

            // Human-readable summary shown in the in-app feed
            $table->string('title');
            $table->text('body');

            // null = unread, datetime = read
            $table->timestamp('read_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'read_at']);
            $table->index('device_activity_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('incident_notifications');
    }
};
