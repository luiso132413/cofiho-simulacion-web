<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    private function disponibles()
    {
        return Vehiculo::with(['modelo.marca', 'tipoVehiculo', 'inventarios.predio'])
            ->where('estado', 'DISPONIBLE')
            ->whereHas('inventarios', function ($q) {
                $q->whereNull('fecha_salida')->whereHas('predio');
            });
    }

    public function index(Request $request)
    {
        $vehiculos = $this->disponibles()->orderBy('id_vehiculo', 'desc')->get();
        $vehiculos = $vehiculos->filter(function ($v) use ($request) {
            $marca = (string) ($v->modelo?->marca?->nombre ?? '');
            $modelo = (string) ($v->modelo?->nombre ?? '');
            $busqueda = trim((string) $request->query('buscar', ''));
            if ($busqueda !== '' && stripos($marca . ' ' . $modelo . ' ' . $v->anio, $busqueda) === false) return false;
            if ($request->filled('condicion') && $v->condicion !== $request->query('condicion')) return false;
            return true;
        })->values();
        return view('catalogo.index', compact('vehiculos'));
    }

    public function show(int $id)
    {
        $vehiculo = $this->disponibles()->where('id_vehiculo', $id)->firstOrFail();
        $inventarioActual = $vehiculo->inventarios->whereNull('fecha_salida')->sortByDesc('id_inventario')->first();
        return view('catalogo.show', compact('vehiculo', 'inventarioActual'));
    }
}
