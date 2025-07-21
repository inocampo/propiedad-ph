<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResidentFormController;

Route::get('/', function () {
    return view('welcome');
});

// Rutas para el formulario público de residentes
Route::prefix('residentes')->group(function () {
    Route::get('/', [ResidentFormController::class, 'index'])->name('residentes.index');
    Route::post('/verificar', [ResidentFormController::class, 'verificarApartamento'])->name('residentes.verificar');
    Route::get('/formulario/{number}', [ResidentFormController::class, 'mostrarFormulario'])->name('residentes.formulario');
    Route::get('/enviar-codigo/{number}', [ResidentFormController::class, 'enviarCodigo'])->name('residentes.enviar-codigo');
    Route::post('/verificar-codigo', [ResidentFormController::class, 'verificarCodigo'])->name('residentes.verificar-codigo');
    Route::post('/guardar', [ResidentFormController::class, 'guardarDatos'])->name('residentes.guardar');
    Route::get('/confirmacion', [ResidentFormController::class, 'confirmacion'])->name('residentes.confirmacion');
});
