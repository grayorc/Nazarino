<?php

use App\Http\Controllers\ElectionController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\InviteController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\InviteResponseController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');


Route::prefix('elections')->group(function () {
    Route::get('/', [ElectionController::class, 'index'])
    ->name('elections.index');

    Route::get('/create', [ElectionController::class, 'create'])
    ->can('unlimited-access')
    ->name('elections.create');

    Route::post('/', [ElectionController::class, 'store'])
    ->can('unlimited-access')
    ->name('elections.store');

    Route::get('/{election}', [ElectionController::class, 'showResult'])
    ->can('view-election', 'election')
    ->name('elections.result');

    Route::get('/{election}/edit', [ElectionController::class, 'edit'])
    ->can('update-election', 'election')
    ->name('elections.edit');

    Route::put('/{election}', [ElectionController::class, 'update'])
    ->can('update-election', 'election')
    ->name('elections.update');

    Route::get('/{election}/ai-analysis', [ElectionController::class, 'getAiAnalysis'])
    ->can('ai-analysis')
    ->name('elections.ai-analysis');

    Route::delete('/{election}', [ElectionController::class, 'destroy'])
    ->can('delete-election', 'election')
    ->name('elections.destroy');

    Route::get('/{election}/options/create', [OptionController::class, 'create'])
    ->can('update-election', 'election')
    ->name('options.create');
    Route::post('/{election}/options', [OptionController::class, 'store'])
    ->can('update-election', 'election')
    ->name('options.store');
    Route::get('/{election}/options/{option}/edit', [OptionController::class, 'edit'])
    ->can('update-election', 'election', 'option')
    ->name('options.edit');
    Route::put('/{election}/options/{option}', [OptionController::class, 'update'])
    ->can('update-election', 'election', 'option')
    ->name('options.update');

    Route::get('/{election}/invite', [InviteController::class, 'index'])
    ->can('invite-to-election')
    ->name('election.invite');

    Route::post('/{election}/send-invite', [InviteController::class, 'sendInvite'])
    ->can('invite-to-election')
    ->name('election.send-invite');
});

Route::prefix('purchase')->group(function () {
    Route::get('/{subscriptionTier:title}',[PurchaseController::class, 'index'])->name('purchase.index');
    Route::post('/payment-process',[PurchaseController::class, 'paymentProcess'])->name('purchase.payment-process');
    Route::post('/verify',[PurchaseController::class, 'verify'])->name('purchase.verify');
});

Route::get('receipts', [ReceiptController::class, 'index'])->name('receipts.index');
Route::get('subscription', [PurchaseController::class, 'subscriptionIndex'])->name('subscription.index');

Route::prefix('notifications')->name('notifications.')->group(function () {
    Route::get('/', [NotificationsController::class, 'index'])->name('index');
    Route::post('/{id}/mark-as-read', [NotificationsController::class, 'markAsRead'])->name('mark-as-read');
    Route::post('/mark-all-as-read', [NotificationsController::class, 'markAllAsRead'])->name('mark-all-as-read');
});

Route::prefix('invites')->name('invites.')->group(function () {
    Route::post('/{invite}/accept', [InviteResponseController::class, 'accept'])->name('accept');
    Route::post('/{invite}/reject', [InviteResponseController::class, 'reject'])->name('reject');
});
