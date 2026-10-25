<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;

class VehiculoController extends Controller
{
    /**
     * RF - Gestión Predial
     *
     * Muestra los vehículos con su predio actual
     * y calcula el stock disponible por predio.
     */
    public function index()
    {
        // Consultar vehículos y sus relaciones.
        $vehiculos = Vehiculo::with([
            'modelo.marca',
            'tipoVehiculo',
            'inventarios.predio',
        ])
        ->orderBy('id_vehiculo', 'asc')
        ->get();

        // Identificar el inventario vigente.
        foreach ($vehiculos as $vehiculo) {

            $inventarioActual = $vehiculo->inventarios
                ->filter(function ($inventario) {
                    return is_null($inventario->fecha_salida);
                })
                ->sortByDesc(function ($inventario) {
                    return sprintf(
                        '%s-%020d',
                        $inventario->fecha_ingreso ?? '',
                        (int) $inventario->id_inventario
                    );
                })
                ->first();

            // No utilizar inventarios históricos
            // como si fueran ubicaciones actuales.
            $vehiculo->setRelation(
                'inventarioActual',
                $inventarioActual
            );
        }

        // Calcular stock disponible por predio.
        $stockPorPredio = $vehiculos
            ->filter(function ($vehiculo) {

                return strtoupper(
                    trim((string) $vehiculo->estado)
                ) === 'DISPONIBLE'
                && $vehiculo->inventarioActual !== null
                && $vehiculo->inventarioActual->predio !== null;

            })
            ->groupBy(function ($vehiculo) {

                return $vehiculo
                    ->inventarioActual
                    ->id_predio;

            })
            ->map(function ($grupo) {

                $predio = $grupo
                    ->first()
                    ->inventarioActual
                    ->predio;

                return [
                    'predio' => $predio->nombre,
                    'stock' => $grupo->count(),
                ];

            })
            ->values();

        // Total disponible con ubicación vigente.
        $stockTotal = $stockPorPredio->sum('stock');

        return view('vehiculos.index', compact(
            'vehiculos',
            'stockPorPredio',
            'stockTotal'
        ));
    }
}
