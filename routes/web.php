<?php

use App\Http\Controllers\MerchantController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // User
    Route::get('users', [UserController::class, 'index'])->name('user.index');

    // Merchant
    Route::get('merchant', [MerchantController::class, 'index'])->name('merchantProfile');

    // Note
    Route::get('/merchants/{merchant}/notes', [NoteController::class, 'getMerchantNotes'])->name('getMerchantNotes');
    Route::get('/users/{user}/notes', [NoteController::class, 'getUserNotes'])->name('getUserNotes');
    Route::apiResource('notes', NoteController::class);
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
