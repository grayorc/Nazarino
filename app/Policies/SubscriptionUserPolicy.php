<?php

namespace App\Policies;

use App\Models\SubscriptionUser;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SubscriptionUserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermission('view-user-subscription');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SubscriptionUser $subscriptionUser): bool
    {
        // Users can view their own subscriptions or admins with permission can view any
        return $user->id === $subscriptionUser->user_id || $user->hasPermission('view-user-subscription');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermission('create-user-subscription');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SubscriptionUser $subscriptionUser): bool
    {
        return $user->hasPermission('edit-user-subscription');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SubscriptionUser $subscriptionUser): bool
    {
        return $user->hasPermission('remove-user-subscription');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SubscriptionUser $subscriptionUser): bool
    {
        return $user->hasPermission('restore-user-subscription');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SubscriptionUser $subscriptionUser): bool
    {
        return $user->hasPermission('force-delete-user-subscription');
    }
}
