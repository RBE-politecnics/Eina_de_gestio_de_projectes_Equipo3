<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')
    ->withoutMiddleware('web')
    ->name('home');

Route::view('/login', 'auth.login')
    ->withoutMiddleware('web')
    ->name('login');

Route::view('/dashboard', 'dashboards.main-dashboard')
    ->name('dashboard');