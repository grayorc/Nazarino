<?php

namespace App\Policies;

use App\Models\User;

class FeaturePolicy
{
    /**
     * Check if user has access to unlimited features
     */
    public function unlimitedAccess(User $user): bool
    {
        return $user->hasSubFeature('unlimited_elections') || $user->elections()->doesntExist();
    }

    /**
     * Check if user has access to charts feature
     */
    public function charts(User $user): bool
    {
        return $user->hasSubFeature('charts');
    }

    /**
     * Check if user has access to Excel export feature
     */
    public function excelExport(User $user): bool
    {
        return $user->hasSubFeature('exel_export');
    }

    /**
     * Check if user has access to create private elections
     */
    public function privateElections(User $user): bool
    {
        return $user->hasSubFeature('private_elections');
    }

    /**
     * Check if user has access to invite people to elections
     */
    public function inviteToElection(User $user): bool
    {
        return $user->hasSubFeature('invite_to_election');
    }

    public function aiAnalysis(User $user): bool
    {
        return $user->hasSubFeature('ai_analysis');
    }
}
