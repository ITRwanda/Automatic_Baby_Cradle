<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Family;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@iot.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // Family Parent
        $family = Family::create([
            'family_name' => 'Twagirimukiza Family',
            'parent_id' => null, // will be updated after parent creation
        ]);

        $parent = User::create([
            'name' => 'Jean Parent',
            'email' => 'parent@iot.com',
            'password' => Hash::make('password123'),
            'role' => 'family_parent',
            'family_id' => $family->id,
        ]);

        $family->update(['parent_id' => $parent->id]);

        // Family Members (limit 3)
        User::create([
            'name' => 'Member One',
            'email' => 'member1@iot.com',
            'password' => Hash::make('password123'),
            'role' => 'family_member',
            'family_id' => $family->id,
        ]);

        User::create([
            'name' => 'Member Two',
            'email' => 'member2@iot.com',
            'password' => Hash::make('password123'),
            'role' => 'family_member',
            'family_id' => $family->id,
        ]);

        User::create([
            'name' => 'Member Three',
            'email' => 'member3@iot.com',
            'password' => Hash::make('password123'),
            'role' => 'family_member',
            'family_id' => $family->id,
        ]);
    }
}
