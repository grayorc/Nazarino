<?php

namespace App\Providers;

use App\Models\Role;
use App\Policies\AdminPolicy;
use App\Policies\RolePolicy;
use App\Policies\UserPolicy;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::automaticallyEagerLoadRelationships();
        Gate::define('view-user', [UserPolicy::class, 'viewAny']);
        Gate::define('remove-user', [UserPolicy::class, 'delete']);
        Gate::define('edit-user', [UserPolicy::class, 'edit']);
    }
}
