<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardExportController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmailController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [EmailController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/export/excel', [DashboardExportController::class, 'excel'])->name('dashboard.export.excel');
    Route::get('/dashboard/export/pdf', [DashboardExportController::class, 'pdf'])->name('dashboard.export.pdf');
    Route::get('/compose', function () {
        return view('compose');
    })->name('compose');
    Route::post('/compose', [EmailController::class, 'store'])->name('compose.store');
    Route::get('/recepients/{email}', [EmailController::class, 'show'])->name('recepients.show');
    Route::get('/draft', function () {
        return view('draft');
    })->name('draft');
});

// ->middleware(['auth', 'verified'])

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
