<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.index');
});

Route::Resource('users', UserController::class);

//Route::post('/password-reset', [UserController::class, 'sendResetLink'])->name('password.email');
