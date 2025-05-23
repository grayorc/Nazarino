<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-role');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role = null): bool
    {
        return $user->hasPermission('view-role');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create-role');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role = null): bool
    {
        return $user->hasPermission('edit-role');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role = null): bool
    {
        return $user->hasPermission('remove-role');
    }

    /**
     * Determine whether the user can assign roles.
     */
    public function assignRole(User $user): bool
    {
        return $user->hasPermission('assign-role');
    }

    /**
     * Determine whether the user can assign permissions to roles.
     */
    public function assignPermission(User $user): bool
    {
        return $user->hasPermission('assign-permission-to-role');
    }
}
