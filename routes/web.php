<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaginesController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TasquesController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')
    ->name('home');

Route::view('/login', 'auth.login')
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.store');

Route::view('/sign-up', 'auth.sign-up')
    ->name('sign.up');

Route::post('/sign-up', [AuthController::class, 'signup'])
    ->name('sign.up.store');

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::view('/dashboard', 'pages.main-dashboard')->name('dashboard');
    Route::view('/roles', 'pages.roles')->name('roles');

    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::post('/tasques', [TasquesController::class, 'store'])->name('tasques.store');
});

// Vistes del projecte (Alex): tauler Kanban, calendari i analítiques
Route::middleware('auth')->group(function (): void {
    Route::get('/tauler/{projecteId?}', [PaginesController::class, 'tauler'])->whereNumber('projecteId')->name('tauler');
    Route::view('/calendari', 'pages.calendari')->name('calendari');
    Route::view('/analitiques', 'pages.analitiques')->name('analitiques');
});

// Els meus projectes i Equip (Alex)
Route::middleware('auth')->group(function (): void {
    Route::get('/projectes', [PaginesController::class, 'projectes'])->name('projectes');
    Route::get('/equip', [PaginesController::class, 'equip'])->name('equip');
    Route::post('/equip', [PaginesController::class, 'afegirMembre'])->name('equip.afegir');
    Route::put('/tasques/{tasca}', [TasquesController::class, 'update'])->name('tasques.update');
    Route::delete('/tasques/{tasca}', [TasquesController::class, 'destroy'])->name('tasques.destroy');
});
