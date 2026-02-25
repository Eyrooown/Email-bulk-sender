<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [EmailController::class, 'index'])->name('dashboard');
    Route::get('/compose', [EmailController::class, 'compose'])->name('compose');
    Route::post('/compose', [EmailController::class, 'store'])->name('compose.store');
});

// ->middleware(['auth', 'verified'])

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
