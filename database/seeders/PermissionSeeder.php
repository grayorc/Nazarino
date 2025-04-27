<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'assign-role',
            'create-role',
            'edit-role',
            'remove-role',
            'view-role',
            'view-election',
            'create-election',
            'edit-election',
            'remove-election',
            'view-admin',
            'edit-admin',
            'remove-admin',
            'view-user',
            'create-user',
            'edit-user',
            'remove-user',

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission
            ]);
        }
    }

}
