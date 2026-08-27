<?php

use App\Http\Controllers\KendaraanController;

// Menggunakan format RESTful dengan ID kendaraan
Route::get('/vehicles/{id}/edit', [KendaraanController::class, 'edit'])->name('kendaraan.edit');
Route::put('/vehicles/{id}', [KendaraanController::class, 'update'])->name('kendaraan.update');