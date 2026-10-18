
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\FinanzaController;
use App\Http\Controllers\InspeccionController;
use App\Http\Controllers\ReporteController;

// Inicio
Route::get('/', function () {
    return view('welcome');
});

// Vehículos
Route::get('/vehiculos', [VehiculoController::class, 'index'])
    ->name('vehiculos.index');

// Inventario
Route::get('/inventario', [InventarioController::class, 'index'])
    ->name('inventario.index');

// Clientes
Route::get('/clientes', [ClienteController::class, 'index'])
    ->name('clientes.index');

Route::get('/clientes/{id}', [ClienteController::class, 'show'])
    ->name('clientes.show');

// Usuarios
Route::get('/usuarios', [UsuarioController::class, 'index'])
    ->name('usuarios.index');

Route::get('/usuarios/{id}', [UsuarioController::class, 'show'])
    ->name('usuarios.show');

// Reservas
Route::get('/reservas', [ReservaController::class, 'index'])
    ->name('reservas.index');

Route::post('/reservas', [ReservaController::class, 'store'])
    ->name('reservas.store');

Route::post('/reservas/fifo', [ReservaController::class, 'storeFIFO'])
    ->name('reservas.fifo');

Route::get('/reservas/{id}', [ReservaController::class, 'show'])
    ->name('reservas.show');

// Ventas
Route::get('/ventas', [VentaController::class, 'index'])
    ->name('ventas.index');

Route::post('/ventas', [VentaController::class, 'store'])
    ->name('ventas.store');

Route::get('/ventas/{id}', [VentaController::class, 'show'])
    ->name('ventas.show');

// Finanzas
Route::get('/finanzas', [FinanzaController::class, 'index'])
    ->name('finanzas.index');

Route::get('/finanzas/{id}/detalles', [FinanzaController::class, 'detalles'])
    ->name('finanzas.detalles');

Route::get('/finanzas/{id}', [FinanzaController::class, 'show'])
    ->name('finanzas.show');

// Inspecciones
Route::get('/inspecciones', [InspeccionController::class, 'index'])
    ->name('inspecciones.index');

Route::get('/inspecciones/{id}', [InspeccionController::class, 'show'])
    ->name('inspecciones.show');

// Reportes
Route::get('/reportes', [ReporteController::class, 'index'])
    ->name('reportes.index');

Route::get('/reportes/fifo', [ReporteController::class, 'inventarioFIFO'])
    ->name('reportes.fifo');

Route::get('/reportes/conciliacion', [ReporteController::class, 'conciliacionFinanciera'])
    ->name('reportes.conciliacion');

Route::get('/reportes/antiguedad', [ReporteController::class, 'antiguedadInventario'])
    ->name('reportes.antiguedad');
