<?php

use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SubFeatureController;
use App\Http\Controllers\Admin\SubscriptionTierController;
use App\Http\Controllers\Admin\SubscriptionUserController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.index');
});

//users
Route::prefix('users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])
        ->can('view-user')
        ->name('index');

    Route::get('/create', [UserController::class, 'create'])
        ->can('create-user')
        ->name('create');

    Route::post('/', [UserController::class, 'store'])
        ->can('create-user')
        ->name('store');

    Route::get('/{user}', [UserController::class, 'show'])
        ->can('view-user')
        ->name('show');

    Route::get('/{user}/edit', [UserController::class, 'edit'])
        ->can('edit-user','user')
        ->name('edit');

    Route::put('/{user}', [UserController::class, 'update'])
        ->can('edit-user','user')
        ->name('update');

    Route::delete('/{user}', [UserController::class, 'destroy'])
        ->can('remove-user','user')
        ->name('destroy');

    Route::get('/{user}/roles', [UserRoleController::class, 'edit'])
        ->can('assign-role')
        ->name('users.roles.edit');

    Route::patch('/{user}/roles', [UserRoleController::class, 'update'])
        ->can('assign-role')
        ->name('users.roles.update');
});

//elections
Route::prefix('elections')->name('elections.')->group(function () {
    Route::get('/', [ElectionController::class, 'index'])
        ->can('view-election')
        ->name('index');

    Route::get('/{election}', [ElectionController::class, 'show'])
        ->can('view-election')
        ->name('show');

    Route::get('/{election}/edit', [ElectionController::class, 'edit'])
        ->can('edit-election')
        ->name('edit');
    Route::put('/{election}', [ElectionController::class, 'update'])
        ->can('edit-election')
        ->name('update');

    Route::delete('/{election}', [ElectionController::class, 'destroy'])
        ->can('delete-election')
        ->name('destroy');
});

//roles
Route::prefix('roles')->name('roles.')->group(function () {
    Route::get('/', [RoleController::class, 'index'])
        ->can('view-role')
        ->name('index');

    Route::get('/create', [RoleController::class, 'create'])
        ->can('create-role')
        ->name('create');

    Route::post('/', [RoleController::class, 'store'])
        ->can('create-role')
        ->name('store');

    Route::get('/{role}/edit', [RoleController::class, 'edit'])
        ->can('edit-role')
        ->name('edit');

    Route::patch('/{role}', [RoleController::class, 'update'])
        ->can('edit-role')
        ->name('update');

    Route::delete('/{role}', [RoleController::class, 'destroy'])
        ->can('remove-role')
        ->name('destroy');
});

//permissions
Route::prefix('permissions')->name('permissions.')->group(function () {
    Route::get('/', [PermissionController::class, 'index'])
        ->can('view-permission')
        ->name('index');

    Route::get('/{permission}/edit', [PermissionController::class, 'edit'])
        ->can('edit-permission')
        ->name('edit');

    Route::patch('/{permission}', [PermissionController::class, 'update'])
        ->can('edit-permission')
        ->name('update');
});

//sub features
Route::prefix('subfeatures')->name('subfeatures.')->group(function () {
    Route::get('/', [SubFeatureController::class, 'index'])
        ->can('view-sub-feature')
        ->name('index');
    Route::get('/{subfeature}/edit', [SubFeatureController::class, 'edit'])
        ->can('edit-sub-feature')
        ->name('edit');
    Route::patch('/{subfeature}', [SubFeatureController::class, 'update'])
        ->can('edit-sub-feature')
        ->name('update');
});

//subscription tiers
Route::prefix('subscription-tiers')->name('subscription-tiers.')->group(function () {
    Route::get('/', [SubscriptionTierController::class, 'index'])
        ->can('view-subscription')
        ->name('index');

    Route::get('/create', [SubscriptionTierController::class, 'create'])
        ->can('create-subscription')
        ->name('create');

    Route::post('/', [SubscriptionTierController::class, 'store'])
        ->can('create-subscription')
        ->name('store');

    Route::get('/{subscriptionTier}/edit', [SubscriptionTierController::class, 'edit'])
        ->can('edit-subscription')
        ->name('edit');

    Route::patch('/{subscriptionTier}', [SubscriptionTierController::class, 'update'])
        ->can('edit-subscription')
        ->name('update');

    Route::delete('/{subscriptionTier}', [SubscriptionTierController::class, 'destroy'])
        ->can('remove-subscription')
        ->name('destroy');
});

//subscription users
Route::prefix('subscription-users')->name('subscription-users.')->group(function () {
    Route::get('/', [SubscriptionUserController::class, 'index'])
        ->can('view-user-subscription')
        ->name('index');

    Route::get('/create', [SubscriptionUserController::class, 'create'])
        ->can('create-user-subscription')
        ->name('create');

    Route::post('/', [SubscriptionUserController::class, 'store'])
        ->can('create-user-subscription')
        ->name('store');

    Route::get('/{subscriptionUser}/edit', [SubscriptionUserController::class, 'edit'])
        ->can('edit-user-subscription')
        ->name('edit');

    Route::patch('/{subscriptionUser}', [SubscriptionUserController::class, 'update'])
        ->can('edit-user-subscription')
        ->name('update');

    Route::delete('/{subscriptionUser}', [SubscriptionUserController::class, 'destroy'])
        ->can('remove-user-subscription')
        ->name('destroy');
});
