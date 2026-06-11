<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Family;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Fetch role IDs
        $adminRoleId   = Role::where('name', 'admin')->first()->id;
        $parentRoleId  = Role::where('name', 'family_parent')->first()->id;
        $memberRoleId  = Role::where('name', 'family_member')->first()->id;

        // Admin user
        User::create([
            'name' => 'System Admin',
            'email' => 'admin@iot.com',
            'password' => Hash::make('password123'),
            'role_id' => $adminRoleId,
        ]);

        // Family Parent first
        $parent = User::create([
            'name' => 'Jean Parent',
            'email' => 'parent@iot.com',
            'password' => Hash::make('password123'),
            'role_id' => $parentRoleId,
        ]);

        // Family with parent_id set
        $family = Family::create([
            'family_name' => 'Twagirimukiza Family',
            'parent_id' => $parent->id,
        ]);

        // Link parent back to family
        $parent->update(['family_id' => $family->id]);

        // Family Members
        $members = [
            ['name' => 'Member One', 'email' => 'member1@iot.com'],
            ['name' => 'Member Two', 'email' => 'member2@iot.com'],
            ['name' => 'Member Three', 'email' => 'member3@iot.com'],
        ];

        foreach ($members as $member) {
            User::create([
                'name' => $member['name'],
                'email' => $member['email'],
                'password' => Hash::make('password123'),
                'role_id' => $memberRoleId,
                'family_id' => $family->id,
            ]);
        }
    }
}
