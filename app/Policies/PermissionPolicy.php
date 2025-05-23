<?php

namespace App\Policies;

use App\Models\Permission;
use App\Models\User;

class PermissionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-permission');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Permission $permission): bool
    {
        return $user->hasPermission('view-permission');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create-permission');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Permission $permission = null): bool
    {
        return $user->hasPermission('edit-permission');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Permission $permission): bool
    {
        return $user->hasPermission('remove-permission');
    }

    /**
     * Determine whether the user can assign permissions to users.
     */
    public function assignToUser(User $user): bool
    {
        return $user->hasPermission('assign-permission-to-user');
    }
}
