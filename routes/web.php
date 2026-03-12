<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\CitaController;
use App\Http\Controllers\AuthController;

// ── Autenticación ────────────────────────────────────────────
Route::get('/',        [AuthController::class, 'showLogin'])->name('login');
Route::post('/login',  [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ── Rutas protegidas ─────────────────────────────────────────
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ── CRUD Pacientes ───────────────────────────────────────
    Route::resource('pacientes', PacienteController::class);
    Route::get('/pacientes-buscar',            [PacienteController::class, 'buscar'])->name('pacientes.buscar');
    Route::get('/pacientes-validar-documento', [PacienteController::class, 'validarDocumento'])->name('pacientes.validar.documento');

    // ── CRUD Citas ───────────────────────────────────────────
    Route::resource('citas', CitaController::class);
    Route::get('/citas-calendario',       [CitaController::class, 'calendario'])->name('citas.calendario');
    Route::get('/citas-eventos',          [CitaController::class, 'eventos'])->name('citas.eventos');
    Route::get('/citas-disponibilidad',   [CitaController::class, 'verificarDisponibilidad'])->name('citas.disponibilidad');
    Route::patch('/citas/{cita}/estado',  [CitaController::class, 'cambiarEstado'])->name('citas.estado');
});
