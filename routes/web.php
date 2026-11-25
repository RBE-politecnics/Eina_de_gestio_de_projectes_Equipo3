<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfiguracioController;
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
});

// API JSON de Kantum (prefix /api, noms api.*)
Route::prefix('api')->name('api.')->group(base_path('routes/api.php'));

// Configuració: tipus de tasca i estats (Alex)
Route::middleware('auth')->prefix('configuracio')->name('configuracio.')->group(function (): void {
    Route::get('/', [ConfiguracioController::class, 'index'])->name('index');
    Route::post('/tipus', [ConfiguracioController::class, 'guardarTipus'])->name('tipus.guardar');
    Route::put('/tipus/{tipus}', [ConfiguracioController::class, 'actualitzarTipus'])->name('tipus.actualitzar');
    Route::delete('/tipus/{tipus}', [ConfiguracioController::class, 'esborrarTipus'])->name('tipus.esborrar');
    Route::post('/estats', [ConfiguracioController::class, 'guardarEstat'])->name('estats.guardar');
    Route::put('/estats/{estat}', [ConfiguracioController::class, 'actualitzarEstat'])->name('estats.actualitzar');
    Route::delete('/estats/{estat}', [ConfiguracioController::class, 'esborrarEstat'])->name('estats.esborrar');
});

// Editar i esborrar projecte (només l'administrador)
Route::middleware('auth')->group(function (): void {
    Route::put('/projects/{project}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
});
