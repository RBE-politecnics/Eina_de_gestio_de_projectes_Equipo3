<?php

use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\TascaController;
use Illuminate\Support\Facades\Route;

// L'API de Kantum. La sessió és la de Laravel (cookie), igual que a la web.
// Es carrega a routes/web.php amb el prefix /api.

Route::post('/login', [AuthApiController::class, 'login'])->name('login');
Route::post('/register', [AuthApiController::class, 'register'])->name('register');

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthApiController::class, 'logout'])->name('logout');
    Route::get('/usuari', [AuthApiController::class, 'usuari'])->name('usuari');

    Route::get('/projectes', [TascaController::class, 'projectes'])->name('projectes');
    Route::get('/projectes/{projecteId}/tasques', [TascaController::class, 'index'])->name('tasques.index');

    Route::post('/tasques', [TascaController::class, 'store'])->name('tasques.store');
    Route::put('/tasques/{tasca}', [TascaController::class, 'update'])->name('tasques.update');
    Route::patch('/tasques/{tasca}/estat', [TascaController::class, 'estat'])->name('tasques.estat');
    Route::patch('/tasques/{tasca}/assignar-me', [TascaController::class, 'assignarMe'])->name('tasques.assignar');
    Route::delete('/tasques/{tasca}', [TascaController::class, 'destroy'])->name('tasques.destroy');
});
