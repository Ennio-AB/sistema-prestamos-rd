<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LegalActionController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

// Autenticación
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Rutas protegidas
Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Clientes
    Route::resource('clientes', ClientController::class)->names('clients');

    // Préstamos
    Route::resource('prestamos', LoanController::class)->names('loans');
    Route::get('prestamos/{loan}/contrato', [LoanController::class, 'contrato'])->name('loans.contrato');

    // Pagos (anidados en préstamo)
    Route::get('pagos', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('prestamos/{loan}/pagos/crear', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('prestamos/{loan}/pagos', [PaymentController::class, 'store'])->name('payments.store');
    Route::delete('pagos/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');

    // Acciones legales (anidadas en préstamo)
    Route::get('legal', [LegalActionController::class, 'index'])->name('legal.index');
    Route::get('prestamos/{loan}/legal/crear', [LegalActionController::class, 'create'])->name('legal.create');
    Route::post('prestamos/{loan}/legal', [LegalActionController::class, 'store'])->name('legal.store');
    Route::delete('legal/{legalAction}', [LegalActionController::class, 'destroy'])->name('legal.destroy');
});
