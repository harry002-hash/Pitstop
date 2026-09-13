<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KendaraanController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\VehicleClaimController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Login Page
Route::get('login', [LoginController::class, 'create'])->name('login');

Route::post('login', [LoginController::class, 'store'])->name('login.attempt');

// Register Page
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    // Customer mengklaim motor (isi KB + password dari bengkel).
    Route::get('/claim', [VehicleClaimController::class, 'create'])
        ->name('vehicle.claim');

    Route::post('/claim', [VehicleClaimController::class, 'store'])
        ->name('vehicle.claim.store');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat');

    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');

    // Customer yang sudah klaim + owner.
    Route::middleware('claimed')->group(function () {

        Route::get('/customer', [CustomerDashboardController::class, 'index'])
            ->name('customer.dashboard');

        Route::get('/customer/status', [CustomerDashboardController::class, 'status'])
            ->name('customer.status');

        Route::get('/customer/notifications', [CustomerDashboardController::class, 'notifications'])
            ->name('customer.notifications');

        // Customer ubah data motornya sendiri.
        Route::get('/customer/vehicle/edit', [KendaraanController::class, 'edit'])
            ->name('customer.vehicle.edit');

        Route::put('/customer/vehicle', [KendaraanController::class, 'update'])
            ->name('customer.vehicle.update');
    });

    // Khusus pemilik bengkel (Ajung) + admin.
    Route::middleware('owner')->prefix('owner')->name('owner.')->group(function () {

        Route::get('/dashboard', [VehicleController::class, 'index'])
            ->name('dashboard');

        Route::get('/notifications', [VehicleController::class, 'notifications'])
            ->name('notifications');

        Route::get('/vehicles', [VehicleController::class, 'index'])
            ->name('vehicles.index');

        Route::post('/vehicles', [VehicleController::class, 'store'])
            ->name('vehicles.store');

        Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])
            ->name('vehicles.update');

        Route::post('/vehicles/{vehicle}/remind', [VehicleController::class, 'remind'])
            ->name('vehicles.remind');
    });

});
