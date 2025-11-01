<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\AdminController;

Route::get('/', [DisplayController::class, 'showDisplay'])->name('display.queue');

Route::post('/queue/update', [DisplayController::class, 'updateQueue'])->name('queue.update');

Route::prefix('dashboard')->name('admin.')->group(function () {
    
    // Rute utama Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');

    // --- Manajemen Buku Tamu (Guest) ---
    Route::get('/guests', [AdminController::class, 'guestsIndex'])->name('guests.index');
    Route::post('/guests', [AdminController::class, 'guestsStore'])->name('guests.store');
    Route::put('/guests/{guest}', [AdminController::class, 'guestsUpdate'])->name('guests.update');
    Route::delete('/guests/{guest}', [AdminController::class, 'guestsDestroy'])->name('guests.destroy');
    Route::get('/guests/export', [AdminController::class, 'guestsExport'])->name('guests.export');
    
    // --- Manajemen Petugas PST ---
    Route::get('/admin-pst', [AdminController::class, 'adminPstIndex'])->name('admin_pst.index');
    Route::post('/admin-pst', [AdminController::class, 'adminPstStore'])->name('admin_pst.store');
    Route::put('/admin-pst/{adminPST}', [AdminController::class, 'adminPstUpdate'])->name('admin_pst.update'); 
    Route::delete('/admin-pst/{adminPst}', [AdminController::class, 'adminPstDestroy'])->name('admin_pst.destroy'); // UBAH {adminPST} menjadi {adminPst}
    Route::patch('/admin-pst/{adminPst}/toggle-status', [AdminController::class, 'adminPstToggleStatus'])->name('admin_pst.toggle_status');
    // --- Manajemen Infografis ---
    Route::get('/infographics', [AdminController::class, 'infographicsIndex'])->name('infographics.index');
    Route::post('/infographics', [AdminController::class, 'infographicsStore'])->name('infographics.store');
    Route::put('/infographics/{infographic}', [AdminController::class, 'infographicsUpdate'])->name('infographics.update');
    Route::delete('/infographics/{infographic}', [AdminController::class, 'infographicsDestroy'])->name('infographics.destroy');
    Route::patch('/infographics/{infographic}/toggle-status', [AdminController::class, 'infographicsToggleStatus'])->name('infographics.toggle_status');
    
    // --- Manajemen Running Text ---
    Route::get('/running-text', [AdminController::class, 'runningTextIndex'])->name('running_texts.index');
    Route::post('/running-text', [AdminController::class, 'runningTextStore'])->name('running_texts.store');
    Route::put('/running-text/{runningText}', [AdminController::class, 'runningTextUpdate'])->name('running_texts.update');
    Route::delete('/running-text/{runningText}', [AdminController::class, 'runningTextDestroy'])->name('running_texts.destroy');
    Route::patch('/running-text/{runningText}/toggle-status', [AdminController::class, 'runningTextToggleStatus'])->name('running_texts.toggle_status');
});