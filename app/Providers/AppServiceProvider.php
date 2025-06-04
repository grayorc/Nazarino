<?php

namespace App\Providers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\SubFeature;
use App\Models\SubscriptionTier;
use App\Models\SubscriptionUser;
use App\Policies\AdminPolicy;
use App\Policies\FeaturePolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\SubFeaturePolicy;
use App\Policies\SubscriptionTierPolicy;
use App\Policies\SubscriptionUserPolicy;
use App\Policies\UserPolicy;
use App\Providers\AIServiceProvider;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Policies\ElectionPolicy;
use App\Policies\TransactionPolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->register(AIServiceProvider::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::automaticallyEagerLoadRelationships();

        // User gates
        Gate::define('view-user', [UserPolicy::class, 'viewAny']);
        Gate::define('remove-user', [UserPolicy::class, 'delete']);
        Gate::define('edit-user', [UserPolicy::class, 'edit']);
        Gate::define('create-user', [UserPolicy::class, 'create']);

        // Role gates
        Gate::define('view-role', [RolePolicy::class, 'viewAny']);
        Gate::define('create-role', [RolePolicy::class, 'create']);
        Gate::define('edit-role', [RolePolicy::class, 'update']);
        Gate::define('remove-role', [RolePolicy::class, 'delete']);
        Gate::define('assign-role', [RolePolicy::class, 'assignRole']);
        Gate::define('assign-permission-to-role', [RolePolicy::class, 'assignPermission']);

        // Permission gates
        Gate::define('view-permission', [PermissionPolicy::class, 'viewAny']);
        Gate::define('create-permission', [PermissionPolicy::class, 'create']);
        Gate::define('edit-permission', [PermissionPolicy::class, 'update']);
        Gate::define('remove-permission', [PermissionPolicy::class, 'delete']);
        Gate::define('assign-permission-to-user', [PermissionPolicy::class, 'assignToUser']);

        // SubscriptionTier gates
        Gate::define('view-subscription', [SubscriptionTierPolicy::class, 'viewAny']);
        Gate::define('create-subscription', [SubscriptionTierPolicy::class, 'create']);
        Gate::define('edit-subscription', [SubscriptionTierPolicy::class, 'update']);
        Gate::define('remove-subscription', [SubscriptionTierPolicy::class, 'delete']);
        Gate::define('restore-subscription', [SubscriptionTierPolicy::class, 'restore']);
        Gate::define('force-delete-subscription', [SubscriptionTierPolicy::class, 'forceDelete']);

        // SubscriptionUser gates
        Gate::define('view-user-subscription', [SubscriptionUserPolicy::class, 'viewAny']);
        Gate::define('create-user-subscription', [SubscriptionUserPolicy::class, 'create']);
        Gate::define('edit-user-subscription', [SubscriptionUserPolicy::class, 'update']);
        Gate::define('remove-user-subscription', [SubscriptionUserPolicy::class, 'delete']);
        Gate::define('restore-user-subscription', [SubscriptionUserPolicy::class, 'restore']);
        Gate::define('force-delete-user-subscription', [SubscriptionUserPolicy::class, 'forceDelete']);

        // SubFeature gates
        Gate::define('view-sub-feature', [SubFeaturePolicy::class, 'viewAny']);
        Gate::define('create-sub-feature', [SubFeaturePolicy::class, 'create']);
        Gate::define('edit-sub-feature', [SubFeaturePolicy::class, 'update']);
        Gate::define('remove-sub-feature', [SubFeaturePolicy::class, 'delete']);
        Gate::define('restore-sub-feature', [SubFeaturePolicy::class, 'restore']);
        Gate::define('force-delete-sub-feature', [SubFeaturePolicy::class, 'forceDelete']);

        // Feature-specific gates
        Gate::define('unlimited-access', [FeaturePolicy::class, 'unlimitedAccess']);
        Gate::define('charts', [FeaturePolicy::class, 'charts']);
        Gate::define('excel-export', [FeaturePolicy::class, 'excelExport']);
        Gate::define('private-elections', [FeaturePolicy::class, 'privateElections']);
        Gate::define('invite-to-election', [FeaturePolicy::class, 'inviteToElection']);
        Gate::define('ai-analysis', [FeaturePolicy::class, 'aiAnalysis']);

        // Election gates
        Gate::define('create-election', [ElectionPolicy::class, 'create']);
        Gate::define('view-election', [ElectionPolicy::class, 'view']);
        Gate::define('update-election', [ElectionPolicy::class, 'update']);
        Gate::define('delete-election', [ElectionPolicy::class, 'delete']);
        Gate::define('excel-export', [FeaturePolicy::class, 'excelExport']);

        //receipt gates
        Gate::define('view-receipt',[TransactionPolicy::class,'view']);
        Gate::define('remove-receipt',[TransactionPolicy::class,'delete']);

    }
}
