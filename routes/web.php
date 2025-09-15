<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Página inicial
Route::view('/', 'welcome')->name('home');

// Rotas de autenticação
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login')->name('login.post');
    Route::post('/logout', 'logout')->name('logout');
});

// Rotas protegidas por autenticação
Route::middleware('auth')->group(function () {
    Route::view('/dashboard', 'dashboard')->name('dashboard');
});