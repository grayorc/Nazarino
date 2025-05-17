<?php

use App\Http\Controllers\ElectionController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\InviteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/elections', [ElectionController::class, 'index'])
    ->name('elections.index');

Route::get('/elections/create', [ElectionController::class, 'create'])
    ->can('unlimited-access')
    ->name('elections.create');

Route::post('/elections', [ElectionController::class, 'store'])
    ->can('unlimited-access')
    ->name('elections.store');

Route::get('/elections/{election}', [ElectionController::class, 'showResult'])

    ->name('elections.result');
Route::get('/elections/{election}/edit', [ElectionController::class, 'edit'])
    ->can('charts')
    ->name('elections.edit');

Route::put('/elections/{election}', [ElectionController::class, 'update'])
    ->name('elections.update');

Route::get('/elections/{election}/ai-analysis', [ElectionController::class, 'getAiAnalysis'])
    ->name('elections.ai-analysis');

Route::delete('/elections/{election}', [ElectionController::class, 'destroy'])->name('elections.destroy');

Route::get('elections/{id}/options/create', [OptionController::class, 'create'])->name('options.create');
Route::post('elections/{id}/options', [OptionController::class, 'store'])->name('options.store');

Route::get('purchase/{subscriptionTier:title}',[PurchaseController::class, 'index'])->name('purchase.index');
Route::post('purchase/payment-process',[PurchaseController::class, 'paymentProcess'])->name('purchase.payment-process');
Route::post('purchase/verify',[PurchaseController::class, 'verify'])->name('purchase.verify');

Route::get('receipts', [ReceiptController::class, 'index'])->name('receipts.index');
Route::get('subscription', [PurchaseController::class, 'subscriptionIndex'])->name('subscription.index');

Route::get('election/invite/{election:id}', [InviteController::class, 'index'])
    ->can('invite-to-election')
    ->name('election.invite');
