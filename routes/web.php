<?php

use App\Http\Controllers\ProfileController;
use App\Models\Role;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
})->name('index');

//Route::get('/dashboard', function () {
//    return view('dash.index');
//})->middleware(['auth', 'verified'])->name('dashboard');

Route::prefix('dashboard')->middleware(['auth'])->group( function () {
    require __DIR__.'/dash.php';
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('election/{id}', [\App\Http\Controllers\ElectionController::class, 'show'])->name('election.show');

Route::post('vote', [\App\Http\Controllers\ElectionController::class, 'vote'])->name('vote');
require __DIR__.'/auth.php';

Route::prefix('admin')->middleware(['AdminMiddleware','auth'])->group( function () {
    require __DIR__.'/admin.php';
});

Route::get('elections/{election_id}/option/{option_id}', [\App\Http\Controllers\OptionController::class, 'show'])->name('option.show');
Route::post('comments', [\App\Http\Controllers\CommentController::class, 'store'])->name('comment.store');
