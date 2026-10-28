<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login')
    ->withoutMiddleware('web')
    ->name('home');

Route::view('/login', 'auth.login')
    ->withoutMiddleware('web')
    ->name('login');

Route::view('/dashboard', 'pages.main-dashboard')
    ->name('dashboard');

Route::view('/roles', 'pages.roles')
->name('roles');