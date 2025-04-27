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
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $mod = Role::firstOrCreate(['name' => 'Moderator']);
        $user = Role::firstOrCreate(['name' => 'User']);

        $admin->permissions()->sync(Permission::whereIn('name', ['assign-role', 'remove-role','view-admin'])->pluck('id'));
        $mod->permissions()->sync(Permission::whereIn('name', ['edit-election', 'view-election'])->pluck('id'));
        $user->permissions()->sync(Permission::whereIn('name', ['view-election'])->pluck('id'));

    }
}
