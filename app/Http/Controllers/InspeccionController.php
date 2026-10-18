<?php

namespace App\Http\Controllers;

use App\Models\Inspeccion;

class InspeccionController extends Controller
{
    public function index()
    {
        $inspecciones = Inspeccion::with([
            'vehiculo',
            'usuario'
        ])
        ->orderBy('id_inspeccion', 'desc')
        ->get();

        return view('inspecciones.index', compact('inspecciones'));
    }

    public function show($id)
    {
        $inspeccion = Inspeccion::with([
            'vehiculo',
            'usuario',
            'detalles',
            'avaluos'
        ])
        ->findOrFail($id);

        return view('inspecciones.show', compact('inspeccion'));
    }
}
