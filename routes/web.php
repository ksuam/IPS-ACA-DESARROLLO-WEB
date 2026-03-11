<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\AuthController;

// ── Autenticación ──────────────────────────────────────────
Route::get('/',           [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',     [AuthController::class, 'login'])->name('login.post');
Route::post('/logout',    [AuthController::class, 'logout'])->name('logout');

// ── Rutas protegidas ────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // CRUD Pacientes
    Route::resource('pacientes', PacienteController::class);

    // Búsqueda AJAX (JavaScript/AJAX explícito para cubrir criterio de rúbrica)
    Route::get('/pacientes-buscar', [PacienteController::class, 'buscar'])
         ->name('pacientes.buscar');

    // Validación AJAX de documento único
    Route::get('/pacientes-validar-documento', [PacienteController::class, 'validarDocumento'])
         ->name('pacientes.validar.documento');
});
