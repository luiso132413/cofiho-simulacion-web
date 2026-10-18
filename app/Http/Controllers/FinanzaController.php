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
        $detalles = DetalleFinanciero::where(
            'id_transaccion',
            $id
        )->get();

        return view('finanzas.detalles', compact('detalles'));
    }
}
