<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjetoController;

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

    // Rotas de projetos
    Route::get('/projetos', [ProjetoController::class, 'index'])->name('projetos.index');
    Route::get('/projetos/novo', [ProjetoController::class, 'create'])->name('projetos.create');
    Route::post('/projetos', [ProjetoController::class, 'store'])->name('projetos.store');
    Route::get('/projetos/{projeto}/editar', [ProjetoController::class, 'edit'])->name('projetos.edit');
    Route::put('/projetos/{projeto}', [ProjetoController::class, 'update'])->name('projetos.update');
    Route::delete('/projetos/{projeto}', [ProjetoController::class, 'destroy'])->name('projetos.destroy');
});