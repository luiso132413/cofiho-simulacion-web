<?php

namespace App\Http\Controllers;

use App\Models\TransaccionFinanciera;
use App\Models\DetalleFinanciero;
use Illuminate\Http\Request;

class FinanzaController extends Controller
{
    public function index()
    {
        $transacciones = TransaccionFinanciera::orderBy(
            'id_transaccion',
            'desc'
        )->get();

        return view('finanzas.index', compact('transacciones'));
    }

    public function show($id)
    {
        $transaccion = TransaccionFinanciera::findOrFail($id);

        return view('finanzas.show', compact('transaccion'));
    }

    public function detalles($id)
{
    $transaccion = TransaccionFinanciera::findOrFail($id);

    $detalles = DetalleFinanciero::where(
        'id_venta',
        $transaccion->id_venta
    )->get();

    return view('finanzas.detalles', compact('detalles'));
}
}
