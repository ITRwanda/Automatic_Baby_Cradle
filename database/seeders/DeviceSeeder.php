<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;
use Illuminate\Support\Str;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        Device::create([
            'device_name' => 'Baby Monitor 1',
            'device_token' => Str::uuid(),
            'family_id' => 1, // assigned to Twagirimukiza Family
        ]);

        Device::create([
            'device_name' => 'Baby Monitor 2',
            'device_token' => Str::uuid(),
            'family_id' => 1,
        ]);
    }
}
