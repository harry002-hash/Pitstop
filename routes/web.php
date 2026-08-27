<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('login', function(){
//     return view('login');
// })->name('login');

// Route::post('login', LoginController::class)->name('login.attempt');

// Route::view('dashboard', 'dashboard')->middleware('auth')->name('dashboard');

// Route::view('register', 'register')->name('register');
// Route::post('register', RegisterController::class)->name('register.store');