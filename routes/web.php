<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;

Route::get('/', function () {
    return view('welcome');
});
//Login Page
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store']);
});

//Register Page
Route::middleware('guest')->group(function () {
    Route::view('register', 'register')->name('register');

    Route::get('/register', [RegisterController::class, 'create'])
        ->name('register');

    Route::post('/register', [RegisterController::class, 'store']);
});
    Route::post('register', RegisterController::class)->name('register.store');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');


Route::get('/dashboard', function () {
    return 'Dashboard — kamu berhasil login!';
})->middleware('auth')->name('dashboard');

Route::get('login', function(){
return view('login');
})->name('login');

Route::post('login', LoginController::class)->name('login.attempt');

Route::view('dashboard', 'dashboard')->middleware('auth')->name('dashboard');

Route::view('register', 'register')->name('register');
