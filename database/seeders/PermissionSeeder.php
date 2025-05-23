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
            ['name' => 'assign-role', 'display_name' => 'دادن نقش'],
            ['name' => 'create-role', 'display_name' => 'ایجاد نقش'],
            ['name' => 'edit-role', 'display_name' => 'ویرایش نقش'],
            ['name' => 'delete-role', 'display_name' => 'حذف نقش'],
            ['name' => 'remove-role', 'display_name' => 'برداشتن نقش'],
            ['name' => 'view-role', 'display_name' => 'مشاهده نقش'],

            // Permission permissions
            ['name' => 'view-permission', 'display_name' => 'مشاهده مجوز'],
            ['name' => 'create-permission', 'display_name' => 'ایجاد مجوز'],
            ['name' => 'edit-permission', 'display_name' => 'ویرایش مجوز'],
            ['name' => 'remove-permission', 'display_name' => 'حذف مجوز'],
            ['name' => 'assign-permission-to-role', 'display_name' => 'اختصاص مجوز به نقش'],
            ['name' => 'assign-permission-to-user', 'display_name' => 'اختصاص مجوز به کاربر'],

            // Election permissions
            ['name' => 'view-election', 'display_name' => 'مشاهده انتخابات'],
            ['name' => 'create-election', 'display_name' => 'ایجاد انتخابات'],
            ['name' => 'edit-election', 'display_name' => 'ویرایش انتخابات'],
            ['name' => 'delete-election', 'display_name' => 'حذف انتخابات'],
            ['name' => 'remove-election', 'display_name' => 'برداشتن انتخابات'],

            // User permissions
            ['name' => 'view-admin', 'display_name' => 'مشاهده ادمین'],
            ['name' => 'edit-admin', 'display_name' => 'ویرایش ادمین'],
            ['name' => 'remove-admin', 'display_name' => 'حذف ادمین'],
            ['name' => 'view-user', 'display_name' => 'مشاهده کاربر'],
            ['name' => 'create-user', 'display_name' => 'ایجاد کاربر'],
            ['name' => 'edit-user', 'display_name' => 'ویرایش کاربر'],
            ['name' => 'remove-user', 'display_name' => 'حذف کاربر'],

            // SubFeature permissions
            ['name' => 'view-sub-feature', 'display_name' => 'مشاهده ویژگی فرعی'],
            ['name' => 'create-sub-feature', 'display_name' => 'ایجاد ویژگی فرعی'],
            ['name' => 'edit-sub-feature', 'display_name' => 'ویرایش ویژگی فرعی'],
            ['name' => 'remove-sub-feature', 'display_name' => 'حذف ویژگی فرعی'],
            ['name' => 'restore-sub-feature', 'display_name' => 'بازیابی ویژگی فرعی'],
            ['name' => 'force-delete-sub-feature', 'display_name' => 'حذف کامل ویژگی فرعی'],

            // SubscriptionTier permissions
            ['name' => 'view-subscription', 'display_name' => 'مشاهده اشتراک'],
            ['name' => 'create-subscription', 'display_name' => 'ایجاد اشتراک'],
            ['name' => 'edit-subscription', 'display_name' => 'ویرایش اشتراک'],
            ['name' => 'remove-subscription', 'display_name' => 'حذف اشتراک'],
            ['name' => 'restore-subscription', 'display_name' => 'بازیابی اشتراک'],
            ['name' => 'force-delete-subscription', 'display_name' => 'حذف کامل اشتراک'],

            // SubscriptionUser permissions
            ['name' => 'view-user-subscription', 'display_name' => 'مشاهده اشتراک کاربر'],
            ['name' => 'create-user-subscription', 'display_name' => 'ایجاد اشتراک کاربر'],
            ['name' => 'edit-user-subscription', 'display_name' => 'ویرایش اشتراک کاربر'],
            ['name' => 'remove-user-subscription', 'display_name' => 'حذف اشتراک کاربر'],
            ['name' => 'restore-user-subscription', 'display_name' => 'بازیابی اشتراک کاربر'],
            ['name' => 'force-delete-user-subscription', 'display_name' => 'حذف کامل اشتراک کاربر'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission['name']
            ], [
                'display_name' => $permission['display_name']
            ]);
        }

        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $modRole = Role::firstOrCreate(['name' => 'Moderator']);

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
