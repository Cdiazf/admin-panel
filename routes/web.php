<?php

use App\Http\Controllers\GastoController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/gastos', [GastoController::class, 'index'])->name('gastos.index');
    Route::post('/gastos', [GastoController::class, 'store'])->name('gastos.store');
    Route::put('/gastos/{gasto}', [GastoController::class, 'update'])->name('gastos.update');
    Route::delete('/gastos/{gasto}', [GastoController::class, 'destroy'])->name('gastos.destroy');

    Route::get('/ingresos', function () {
        return view('ingresos.index');
    })->name('ingresos.index');

    Route::get('/flujo', function () {
        return view('flujo.index');
    })->name('flujo.index');
});

require __DIR__ . '/auth.php';
