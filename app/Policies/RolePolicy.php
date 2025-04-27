<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function assignRole(User $user): bool
    {
        return $user->hasPermission('assign-role');
    }

    public function removeRole(User $user): bool
    {
        return $user->hasPermission('remove-role');
    }

    public function viewElection(User $user): bool
    {
        return $user->hasPermission('view-election');
    }

    public function editElection(User $user): bool
    {
        return $user->hasPermission('edit-election');
    }

    public function view(User $user, User $model): bool
    {
//        dd();
        return true;
//        return $user->hasRole('admin') || $user->hasPermission('view-admin');
    }


}
