<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
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
    ->can('edit-user')
    ->name('users.edit');
Route::put('/users/{user}', [UserController::class, 'update'])
    ->can('edit-user')
    ->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])
    ->can('remove-user')
    ->name('users.destroy');

//elections


//permissions
