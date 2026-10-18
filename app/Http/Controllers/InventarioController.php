<?php

namespace App\Http\Controllers;

use App\Models\InventarioPredio;

class InventarioController extends Controller
{
    public function index()
    {
        $inventarios = InventarioPredio::with([
            'vehiculo.modelo.marca',
            'predio'
        ])
        ->orderBy('id_inventario', 'asc')
        ->get();

        return view('inventario.index', compact('inventarios'));
    }
}
