<?php

use App\Http\Controllers\ElectionController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dash.index');
})->name('dashboard');

Route::Resource('elections', \App\Http\Controllers\ElectionController::class);

Route::get('elections/{id}/options/create', [OptionController::class, 'create'])->name('options.create');
Route::post('elections/{id}/options', [OptionController::class, 'store'])->name('options.store');

Route::get('election/{election}', [ElectionController::class, 'showResult'])->name('elections.result');
Route::get('election/{election}/edit', [ElectionController::class, 'edit'])->name('elections.edit');
Route::put('election/{election}/update', [ElectionController::class, 'update'])->name('elections.update');


//Route::get('test', function () {
//    return view('dash.options.create');
//});

//Route::post('test1', OptionController::class);
