<?php

use App\Http\Controllers\MerchantController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\Settings\ProfileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('merchant', [MerchantController::class, 'index'])->name('profile');
    Route::get('/merchants/{merchant}/notes', [NoteController::class, 'getMerchantNotes'])->name('getMerchantNotes');
    Route::apiResource('notes', NoteController::class);
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
