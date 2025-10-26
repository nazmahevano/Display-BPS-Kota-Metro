<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DisplayController;
use App\Http\Controllers\DisplayAdminController; // Controller Admin (Manajemen Display)
use App\Http\Controllers\AdminGuestController; // Controller Manajemen Buku Tamu
use App\Http\Controllers\HomeController;

Route::get('/', [DisplayController::class, 'showDisplay'])->name('display.queue');

Route::post('/queue/update', [DisplayController::class, 'updateQueue'])->name('queue.update');