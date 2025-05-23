<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController extends Controller
{
    /**
     * Show the form for editing user roles and permissions
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        $permissions = Permission::all();

        return view('admin.users.roles', compact('user', 'roles', 'permissions'));
    }

    /**
     * Update user roles and permissions
     */
    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'roles' => ['nullable', 'array'],
            'permissions' => ['nullable', 'array'],
        ]);

        // Sync roles
        if(isset($data['roles'])) {
            $user->roles()->sync($data['roles']);
        } else {
            $user->roles()->detach();
        }

        // Sync permissions
        if(isset($data['permissions'])) {
            $user->permissions()->sync($data['permissions']);
        } else {
            $user->permissions()->detach();
        }

        return redirect()->route('admin.users.index')->with('success', 'نقش‌ها و دسترسی‌های کاربر با موفقیت بروزرسانی شدند');
    }
}
