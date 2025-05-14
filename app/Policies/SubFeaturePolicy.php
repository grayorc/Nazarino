<?php

namespace App\Policies;

use App\Models\SubFeature;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubFeaturePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-sub-feature');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SubFeature $subFeature): bool
    {
        return $user->hasPermission('view-sub-feature');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create-sub-feature');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SubFeature $subFeature): bool
    {
        return $user->hasPermission('edit-sub-feature');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SubFeature $subFeature): bool
    {
        return $user->hasPermission('remove-sub-feature');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SubFeature $subFeature): bool
    {
        return $user->hasPermission('restore-sub-feature');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SubFeature $subFeature): bool
    {
        return $user->hasPermission('force-delete-sub-feature');
    }
}
