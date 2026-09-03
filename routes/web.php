<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ChatController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', function(){
return view('login');
})->name('login');

Route::post('login', LoginController::class)->name('login.attempt');

Route::view('dashboard', 'dashboard')->middleware('auth')->name('dashboard');

// Chat Test
Route::middleware('auth')->group(function () {
    Route::get('/chat',[ChatController::class, 'index'])->name('chat');

    Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
});