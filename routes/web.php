<?php

use App\Http\Controllers\DashboardExportController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\ProposalExportController;
use App\Livewire\ProposalEditor;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/proposal/print-view', function () {
    return view('proposals.print');
})->name('proposal.print-view');

Route::middleware(['auth'])->group(function () {
    Route::redirect('/dashboard', '/inbox');
    Route::get('/inbox', [EmailController::class, 'index'])->name('dashboard');
    Route::get('/inbox/export/excel', [DashboardExportController::class, 'excel'])->name('dashboard.export.excel');
    Route::get('/inbox/export/pdf', [DashboardExportController::class, 'pdf'])->name('dashboard.export.pdf');
    Route::get('/compose', function () {
        return view('compose');
    })->name('compose');
    Route::post('/compose', [EmailController::class, 'store'])->name('compose.store');
    Route::get('/recepients/{email}', [EmailController::class, 'show'])->name('recepients.show');
    Route::get('/draft', function () {
        return view('draft');
    })->name('draft');
    Route::get('/archive', function () {
        return view('archive');
    })->name('archive');
    Route::get('/proposal', [ProposalController::class, 'index'])->name('proposal');
    Route::post('/proposal', [ProposalController::class, 'store'])->name('proposal.store');
    Route::get('/proposal/print', [ProposalController::class, 'print'])->name('proposal.print');
    Route::get('/proposal/{proposal}/edit', ProposalEditor::class)->name('proposal.edit');
    Route::delete('/proposal/{proposal}', [ProposalController::class, 'destroy'])->name('proposal.destroy');
    Route::get('/proposal/{proposal}/export/pdf', [ProposalExportController::class, 'pdf'])->name('proposal.export.pdf');
    Route::get('/proposal/{proposal}/preview', [ProposalExportController::class, 'preview'])->name('proposal.preview');

    /** Isolated test page for ProposalSlide Livewire (not wired to proposal editor). */
    Route::get('/proposal-slide-test', function () {
        return view('proposal-slide-test');
    })->name('proposal-slide.test');

    Route::get('/accounts', function () {
        abort_unless(auth()->user()?->is_admin, 403);

        return view('accounts');
    })->name('accounts');
});

// ->middleware(['auth', 'verified'])

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
