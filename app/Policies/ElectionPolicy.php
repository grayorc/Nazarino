<?php

namespace App\Policies;

use App\Models\Election;
use App\Models\User;
use App\Models\Option;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ElectionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, string|Election $election = null): bool
    {
        if($election == null){
            return $user->hasPermission('view-election');
        }
        if(is_numeric($election)){
            $election = Election::find($election);
        }
        return $election->is_public ? true : auth()->check() && (auth()->user()->InviteCheck($election->id) || $election->user_id == auth()->user()->id);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, Election $election): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, string|Election $election): bool
    {
        if(is_numeric($election)){
            $election = Election::find($election);
        }
        return $election->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, string|Election $election = null): bool
    {
        return $user->hasPermission('delete-election');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Election $election): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Election $election): bool
    {
        return false;
    }
}
