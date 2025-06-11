<?php

use App\Http\Controllers\ProfileController;
use App\Models\Role;
use App\Models\SubscriptionTier;
use App\Models\Election;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $subscriptionTiers = SubscriptionTier::with('subFeatures')
        ->orderBy('price')
        ->get();

    return view('index', compact('subscriptionTiers'));
})->name('index');

Route::prefix('dashboard')->middleware(['auth'])->group( function () {
    require __DIR__.'/dash.php';
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::post('vote', [\App\Http\Controllers\ElectionController::class, 'vote'])->name('vote');
require __DIR__.'/auth.php';

Route::prefix('admin')->name('admin.')->middleware(['AdminMiddleware','auth'])->group( function () {
    require __DIR__.'/admin.php';
});

Route::get('elections', [\App\Http\Controllers\ElectionController::class, 'feed'])->name('elections.feed');

Route::prefix('elections')->group(function () {
    Route::get('{election}', [\App\Http\Controllers\ElectionController::class, 'show'])
        ->middleware('VerifyElectionStatus')
        ->can('view-election', 'election')
        ->name('election.show');

    Route::get('{election}/option/{option}', [\App\Http\Controllers\OptionController::class, 'show'])
        ->middleware('VerifyElectionStatus')
        ->can('view-election', 'election')
        ->name('option.show');

    Route::get('{election}/ai-analysis', [\App\Http\Controllers\ElectionController::class, 'getAiAnalysis'])
        ->middleware('auth')
        ->can('ai-analysis')
        ->name('election.ai-analysis');
});

Route::get('options/{option}/ai-summary', [\App\Http\Controllers\OptionController::class, 'getAiSummary'])
    ->middleware('auth')
    ->can('ai-analysis')
    ->name('option.ai-summary');

Route::post('comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comment.store');
Route::delete('comments/{id}', [\App\Http\Controllers\CommentController::class, 'destroy'])->name('comment.destroy')->middleware('auth');

Route::get('users/{user:username}', [\App\Http\Controllers\UserProfileController::class, 'show'])->name('users.profile');
Route::post('users/{user:username}/follow', [\App\Http\Controllers\UserProfileController::class, 'follow'])->name('users.follow');
Route::get('users/{user:username}/followers', [\App\Http\Controllers\UserProfileController::class, 'followers'])->name('users.followers');
Route::get('users/{user:username}/followings', [\App\Http\Controllers\UserProfileController::class, 'followings'])->name('users.followings');
