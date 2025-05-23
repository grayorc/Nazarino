<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Role permissions
            'assign-role',
            'create-role',
            'edit-role',
            'delete-role',
            'remove-role',
            'view-role',

            // Permission permissions
            'view-permission',
            'create-permission',
            'edit-permission',
            'remove-permission',
            'assign-permission-to-role',
            'assign-permission-to-user',

            // Election permissions
            'view-election',
            'create-election',
            'edit-election',
            'delete-election',
            'remove-election',

            // User permissions
            'view-admin',
            'edit-admin',
            'remove-admin',
            'view-user',
            'create-user',
            'edit-user',
            'remove-user',

            // SubFeature permissions
            'view-sub-feature',
            'create-sub-feature',
            'edit-sub-feature',
            'remove-sub-feature',
            'restore-sub-feature',
            'force-delete-sub-feature',

            // SubscriptionTier permissions
            'view-subscription',
            'create-subscription',
            'edit-subscription',
            'remove-subscription',
            'restore-subscription',
            'force-delete-subscription',

            // SubscriptionUser permissions
            'view-user-subscription',
            'create-user-subscription',
            'edit-user-subscription',
            'remove-user-subscription',
            'restore-user-subscription',
            'force-delete-user-subscription',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission
            ]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $modRole = Role::firstOrCreate(['name' => 'Moderator']);
        $userRole = Role::firstOrCreate(['name' => 'User']);

        // Admin role - gets all permissions
        $allPermissions = Permission::all();
        foreach ($allPermissions as $permission) {
            DB::table('permission_role')->insert([
                'permission_id' => $permission->id,
                'role_id' => $adminRole->id,
            ]);
        }

        // Moderator role - gets specific permissions
        $modPermissions = [
            'view-election',
            'create-election',
            'delete-election',
            'remove-election',
            'view-user',
            'view-role',
            'view-permission',
            'view-sub-feature',
            'view-subscription',
            'view-user-subscription'
        ];

        foreach ($modPermissions as $permName) {
            $permission = Permission::where('name', $permName)->first();
            if ($permission) {
                DB::table('permission_role')->insert([
                    'permission_id' => $permission->id,
                    'role_id' => $modRole->id,
                ]);
            }
        }
    }
}
