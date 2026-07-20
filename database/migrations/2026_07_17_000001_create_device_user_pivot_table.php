<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Many-to-many: one device can be assigned to many caregivers
        Schema::create('device_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')
                  ->constrained('devices')
                  ->cascadeOnDelete();
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->timestamps();

            // A caregiver can only be assigned to a device once
            $table->unique(['device_id', 'user_id']);
        });

        // Migrate existing devices.user_id assignments into the pivot table
        $existing = DB::table('devices')
            ->whereNotNull('user_id')
            ->select('id', 'user_id')
            ->get();

        foreach ($existing as $row) {
            DB::table('device_user')->insertOrIgnore([
                'device_id'  => $row->id,
                'user_id'    => $row->user_id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('device_user');
    }
};
