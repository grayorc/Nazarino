<?php

use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Models\Election;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.index');
})
//    ->can('view','User')
//    ->middleware('can:view,User')
;

//users
Route::get('/users', [UserController::class, 'index'])
    ->can('view-user')
    ->name('users.index');
Route::get('/users/create', [UserController::class, 'create'])
    ->can('create-user')
    ->name('users.create');
Route::post('/users', [UserController::class, 'store'])
    ->can('create-user')
    ->name('users.store');
Route::get('/users/{user}', [UserController::class, 'show'])
    ->can('view-user')
    ->name('users.show');
Route::get('/users/{user}/edit', [UserController::class, 'edit'])
    ->can('edit-user','user')
    ->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])
    ->can('edit-user','user')
    ->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])
    ->can('remove-user','user')
    ->name('users.destroy');

//elections
Route::get('/elections', [ElectionController::class, 'index'])
    // ->can('view-user')
    ->name('elections.index');
Route::get('/elections/create', [ElectionController::class, 'create'])
    // ->can('create-user')
    ->name('elections.create');
Route::post('/elections', [ElectionController::class, 'store'])
    // ->can('create-user')
    ->name('elections.store');
Route::get('/elections/{user}', [ElectionController::class, 'show'])
    // ->can('view-user')
    ->name('elections.show');
Route::get('/elections/{user}/edit', [ElectionController::class, 'edit'])
    // ->can('edit-user')
    ->name('elections.edit');
Route::put('/elections/{user}', [ElectionController::class, 'update'])
    // ->can('edit-user')
    ->name('elections.update');
Route::delete('/elections/{user}', [ElectionController::class, 'destroy'])
    // ->can('remove-user')
    ->name('elections.destroy');

//permissions
