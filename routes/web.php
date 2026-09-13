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
    return view('welcome');
});

// Login Page
Route::get('login', [LoginController::class, 'create'])->name('login');

Route::post('login', [LoginController::class, 'store'])->name('login.attempt');

Route::view('dashboard', 'dashboard')->middleware('auth')->name('dashboard');

Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

// Register Page
Route::get('/register', [RegisterController::class, 'create'])->name('register');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

// Chat Test
Route::middleware('auth')->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat');

    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

// Customer mengklaim motor (isi KB + password dari bengkel).
Route::get('/claim', [VehicleClaimController::class, 'create'])
    ->name('vehicle.claim');

Route::post('/claim', [VehicleClaimController::class, 'store'])
    ->name('vehicle.claim.store');

// Customer yang sudah klaim + owner.
Route::middleware('claimed')->group(function () {

    Route::get('/customer', [CustomerDashboardController::class, 'index'])
        ->name('customer.dashboard');

    Route::get('/customer/status', [CustomerDashboardController::class, 'status'])
        ->name('customer.status');

    Route::get('/chat', [ChatController::class, 'index'])
        ->name('chat');

    Route::post('/chat/send', [ChatController::class, 'send'])
        ->name('chat.send');
});

// Khusus pemilik bengkel (Ajung) + admin.
Route::middleware('owner')->prefix('owner')->name('owner.')->group(function () {

    Route::get('/vehicles', [VehicleController::class, 'index'])
        ->name('vehicles.index');

    Route::get('/vehicles/create', [VehicleController::class, 'create'])
        ->name('vehicles.create');

    Route::post('/vehicles', [VehicleController::class, 'store'])
        ->name('vehicles.store');

    Route::get('/vehicles/{vehicle}/edit', [VehicleController::class, 'edit'])
        ->name('vehicles.edit');

    Route::put('/vehicles/{vehicle}', [VehicleController::class, 'update'])
        ->name('vehicles.update');
});

// Menggunakan format RESTful dengan ID kendaraan
Route::get('/vehicles/{id}/edit', [KendaraanController::class, 'edit'])->name('vehicle.edit');
Route::put('/vehicles/{id}', [KendaraanController::class, 'update'])->name('vehicle.update');

Route::get('/user/dashboard', fn () => view('user.dashboard'));
