<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\VehiculoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\FinanzaController;
use App\Http\Controllers\InspeccionController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CatalogoController;

// Inicio
Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
});

// Rutas protegidas
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Vehículos - Todos los roles
    Route::middleware('rol:ADMINISTRADOR,VENDEDOR,INSPECTOR')->group(function () {
        Route::get('/vehiculos', [VehiculoController::class, 'index'])->name('vehiculos.index');

        Route::middleware('rol:ADMINISTRADOR')->group(function () {
            Route::get('/vehiculos/create', [VehiculoController::class, 'create'])->name('vehiculos.create');
            Route::post('/vehiculos', [VehiculoController::class, 'store'])->name('vehiculos.store');
        });
    });

    // Inventario - Todos los roles
    Route::middleware('rol:ADMINISTRADOR,VENDEDOR,INSPECTOR')->group(function () {
        Route::get('/inventario', [InventarioController::class, 'index'])->name('inventario.index');
    });

    // Clientes - Administrador y vendedor
    Route::middleware('rol:ADMINISTRADOR,VENDEDOR')->group(function () {
        Route::get('/clientes', [ClienteController::class, 'index'])->name('clientes.index');
        Route::get('/clientes/create', [ClienteController::class, 'create'])->name('clientes.create');
        Route::post('/clientes', [ClienteController::class, 'store'])->name('clientes.store');
        Route::get('/clientes/{id}/edit', [ClienteController::class, 'edit'])->name('clientes.edit');
        Route::put('/clientes/{id}', [ClienteController::class, 'update'])->name('clientes.update');
        Route::get('/clientes/{id}', [ClienteController::class, 'show'])->name('clientes.show');
        Route::patch('/clientes/{id}/estado', [ClienteController::class, 'toggleEstado'])->name('clientes.estado');
    });

    // Usuarios - Solo administrador
    Route::middleware('rol:ADMINISTRADOR')->group(function () {
        Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
        Route::get('/usuarios/create', [UsuarioController::class, 'create'])->name('usuarios.create');
        Route::post('/usuarios', [UsuarioController::class, 'store'])->name('usuarios.store');
        Route::get('/usuarios/{id}/edit', [UsuarioController::class, 'edit'])->name('usuarios.edit');
        Route::put('/usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
        Route::patch('/usuarios/{id}/estado', [UsuarioController::class, 'toggleEstado'])->name('usuarios.estado');
        Route::get('/usuarios/{id}', [UsuarioController::class, 'show'])->name('usuarios.show');
    });

    // Reservas - Administrador y vendedor
    Route::middleware('rol:ADMINISTRADOR,VENDEDOR')->group(function () {
        Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index');
        Route::post('/reservas/checkout', [ReservaController::class, 'checkout'])->name('reservas.checkout');
        Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
        Route::post('/reservas/fifo', [ReservaController::class, 'storeFIFO'])->name('reservas.fifo');
        Route::post('/reservas/{id}/enganche', [ReservaController::class, 'registrarEnganche'])->name('reservas.enganche');
        Route::post('/reservas/{id}/devolucion', [ReservaController::class, 'registrarDevolucion'])->name('reservas.devolucion');
        Route::get('/reservas/{id}', [ReservaController::class, 'show'])->name('reservas.show');
    });

    // Ventas - Administrador y vendedor
    Route::middleware('rol:ADMINISTRADOR,VENDEDOR')->group(function () {
        Route::get('/ventas', [VentaController::class, 'index'])->name('ventas.index');
        Route::post('/ventas', [VentaController::class, 'store'])->name('ventas.store');
        Route::get('/ventas/{id}', [VentaController::class, 'show'])->name('ventas.show');
    });

    // Finanzas - Solo administrador
    Route::middleware('rol:ADMINISTRADOR')->group(function () {
        Route::get('/finanzas', [FinanzaController::class, 'index'])->name('finanzas.index');
        Route::get('/finanzas/{id}/detalles', [FinanzaController::class, 'detalles'])->name('finanzas.detalles');
        Route::get('/finanzas/{id}', [FinanzaController::class, 'show'])->name('finanzas.show');
    });

    // Inspecciones - Administrador e inspector
    Route::middleware('rol:ADMINISTRADOR,INSPECTOR')->group(function () {
        Route::get('/inspecciones', [InspeccionController::class, 'index'])->name('inspecciones.index');
        Route::post('/inspecciones', [InspeccionController::class, 'store'])->name('inspecciones.store');
        Route::patch('/inspecciones/{id}/finalizar', [InspeccionController::class, 'finalizar'])->name('inspecciones.finalizar');
        Route::post('/inspecciones/avaluos', [InspeccionController::class, 'storeAvaluo'])->name('inspecciones.avaluos.store');
        Route::patch('/inspecciones/avaluos/{id}/decidir', [InspeccionController::class, 'decidirAvaluo'])
            ->middleware('rol:ADMINISTRADOR')->name('inspecciones.avaluos.decidir');
        Route::patch('/vehiculos/{id}/precio', [VehiculoController::class, 'actualizarPrecio'])
            ->middleware('rol:ADMINISTRADOR')->name('vehiculos.precio.actualizar');
        Route::get('/inspecciones/{id}', [InspeccionController::class, 'show'])->name('inspecciones.show');
    });

    // Reportes - Solo administrador
    Route::middleware('rol:ADMINISTRADOR')->group(function () {
        Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
        Route::get('/reportes/inventario', [ReporteController::class, 'inventario'])->name('reportes.inventario');
        Route::get('/reportes/ventas', [ReporteController::class, 'ventas'])->name('reportes.ventas');
        Route::get('/reportes/fifo', function () {
            return redirect()->route('reportes.inventario', ['vista' => 'fifo']);
        })->name('reportes.fifo');
        Route::get('/reportes/conciliacion', [ReporteController::class, 'conciliacionFinanciera'])->name('reportes.conciliacion');
        Route::get('/reportes/antiguedad', function () {
            return redirect()->route('reportes.inventario', ['vista' => 'antiguedad']);
        })->name('reportes.antiguedad');
        Route::get('/reportes/clientes', [ReporteController::class, 'clientes'])->name('reportes.clientes');
    });
}); // Fin de rutas protegidas

// Autenticación
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Catálogo público
Route::get('/catalogo', [CatalogoController::class, 'index'])->name('catalogo.index');
Route::get('/catalogo/{id}', [CatalogoController::class, 'show'])->whereNumber('id')->name('catalogo.show');

// API pública de prueba - COFIHO V2 (solo lectura)
Route::get('/api/vehiculos', function () {
    $vehiculos = DB::connection('oracle')
        ->table('VEHICULO')
        ->select('ID_VEHICULO', 'VIN', 'ANIO', 'COLOR', 'CONDICION', 'ESTADO', 'PRECIO_LISTA')
        ->where('ESTADO', 'DISPONIBLE')
        ->orderBy('ID_VEHICULO')
        ->get();

    return response()->json([
        'success' => true,
        'total' => $vehiculos->count(),
        'data' => $vehiculos,
    ]);
});
