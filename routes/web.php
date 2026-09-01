<?php

use App\Http\Controllers\VehicleController;

Route::get('/vehicles/{id}/edit', [VehicleController::class, 'edit'])->name('vehicles.edit');
Route::put('/vehicles/{id}', [VehicleController::class, 'update'])->name('vehicles.update');

Route::get('/vehicles/{id}/reminder', [VehicleController::class, 'reminder'])->name('vehicles.reminder');
Route::post('/vehicles/{id}/reminder', [VehicleController::class, 'kirimPemberitahuan'])->name('vehicles.kirimPemberitahuan');