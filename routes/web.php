<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SorteioController;

Route::get('/', [SorteioController::class, 'index'])->name('sorteio.index');
Route::post('/reserve', [SorteioController::class, 'reserve'])->name('sorteio.reserve');
Route::post('/update-status', [SorteioController::class, 'updateStatus'])->name('sorteio.update-status');
Route::get('/api/numbers', [SorteioController::class, 'getNumbers'])->name('sorteio.get-numbers');

// Rotas administrativas
Route::get('/admin', [SorteioController::class, 'admin'])->name('sorteio.admin');
Route::post('/admin/login', [SorteioController::class, 'adminLogin'])->name('sorteio.admin-login');
Route::get('/admin/logout', [SorteioController::class, 'adminLogout'])->name('sorteio.admin-logout');
Route::post('/admin/update-status', [SorteioController::class, 'adminUpdateStatus'])->name('sorteio.admin-update-status');
Route::post('/admin/clear-reservation', [SorteioController::class, 'clearReservation'])->name('sorteio.clear-reservation');
