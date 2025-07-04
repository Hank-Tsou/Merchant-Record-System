<?php

use App\Http\Controllers\MerchantController;
use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get('merchant', [MerchantController::class, 'index'])->name('merchantProfile');
    Route::get('/merchants/{merchant}/notes', [NoteController::class, 'getMerchantNotes'])->name('getMerchantNotes');
    Route::apiResource('notes', NoteController::class);
});


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
