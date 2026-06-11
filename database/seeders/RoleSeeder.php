<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'admin', 'description' => 'System administrator'],
            ['name' => 'family_parent', 'description' => 'Family parent'],
            ['name' => 'family_member', 'description' => 'Family member'],
        ]);
    }
}
