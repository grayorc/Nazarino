<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Just create the roles, permissions will be assigned in PermissionSeeder
        Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Moderator']);
        Role::firstOrCreate(['name' => 'User']);
    }
}
