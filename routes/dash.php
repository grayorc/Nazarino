<?php

use App\Http\Controllers\ElectionController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dash.index');
})->name('dashboard');

Route::get('/elections', [ElectionController::class, 'index'])->name('elections.index');
Route::get('/elections/create', [ElectionController::class, 'create'])->name('elections.create');
Route::post('/elections', [ElectionController::class, 'store'])->name('elections.store');
Route::get('/elections/{election}', [ElectionController::class, 'showResult'])->name('elections.result');
Route::get('/elections/{election}/edit', [ElectionController::class, 'edit'])->name('elections.edit');
Route::put('/elections/{election}', [ElectionController::class, 'update'])->name('elections.update');
Route::delete('/elections/{election}', [ElectionController::class, 'destroy'])->name('elections.destroy');

Route::get('elections/{id}/options/create', [OptionController::class, 'create'])->name('options.create');
Route::post('elections/{id}/options', [OptionController::class, 'store'])->name('options.store');

Route::get('purchase/{subscriptionTier:title}',[PurchaseController::class, 'index'])->name('purchase.index');
Route::post('purchase/payment-process',[PurchaseController::class, 'paymentProcess'])->name('purchase.payment-process');
Route::post('purchase/verify',[PurchaseController::class, 'verify'])->name('purchase.verify');
