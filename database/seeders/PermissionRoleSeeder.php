<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing pivot data to prevent duplicates
        DB::table('permission_role')->truncate();

        // Admin role - gets all permissions
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $allPermissions = Permission::all();
            foreach ($allPermissions as $permission) {
                DB::table('permission_role')->insert([
                    'permission_id' => $permission->id,
                    'role_id' => $adminRole->id,
                ]);
            }
        }

        // Moderator role - gets specific permissions
        $modRole = Role::where('name', 'Moderator')->first();
        if ($modRole) {
            $modPermissions = [
                'view-election',
                'create-election',
                'edit-election',
                'remove-election',
                'view-user'
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

        // User role - gets basic permissions
        $userRole = Role::where('name', 'User')->first();
        if ($userRole) {
            $userPermissions = [
                'view-election',
                'view-user'
            ];
            
            foreach ($userPermissions as $permName) {
                $permission = Permission::where('name', $permName)->first();
                if ($permission) {
                    DB::table('permission_role')->insert([
                        'permission_id' => $permission->id,
                        'role_id' => $userRole->id,
                    ]);
                }
            }
        }
    }
}
