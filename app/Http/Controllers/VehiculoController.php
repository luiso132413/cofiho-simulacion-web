<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;

class VehiculoController extends Controller
{
    public function index()
    {
        $vehiculos = Vehiculo::with([
            'modelo.marca',
            'tipoVehiculo'
        ])
        ->orderBy('id_vehiculo', 'asc')
        ->get();

        return view('vehiculos.index', compact('vehiculos'));
    }
}
