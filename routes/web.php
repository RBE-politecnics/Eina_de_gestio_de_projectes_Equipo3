<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

<<<<<<< Updated upstream
Route::get('/', function () {
    return view('welcome');
=======
Route::redirect('/', '/login')
    ->withoutMiddleware('web')
    ->name('home');

Route::view('/login', 'auth.login')
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.store');

Route::middleware('auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::view('/dashboard', 'pages.main-dashboard')->name('dashboard');
    Route::view('/roles', 'pages.roles')->name('roles');

    Route::resource('projects', ProjectController::class);
>>>>>>> Stashed changes
});
