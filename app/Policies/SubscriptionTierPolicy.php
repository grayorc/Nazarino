<?php

namespace App\Policies;

use App\Models\SubscriptionTier;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubscriptionTierPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-subscription');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SubscriptionTier $subscriptionTier = null): bool
    {
        return $user->hasPermission('view-subscription');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create-subscription');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SubscriptionTier $subscriptionTier = null): bool
    {
        return $user->hasPermission('edit-subscription');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SubscriptionTier $subscriptionTier = null): bool
    {
        return $user->hasPermission('remove-subscription');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SubscriptionTier $subscriptionTier): bool
    {
        return $user->hasPermission('restore-subscription');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SubscriptionTier $subscriptionTier = null): bool
    {
        return $user->hasPermission('force-delete-subscription');
    }
}
