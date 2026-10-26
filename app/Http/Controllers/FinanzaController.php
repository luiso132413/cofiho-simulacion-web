<?php

namespace App\Http\Controllers;

use App\Models\TransaccionFinanciera;
use App\Models\DetalleFinanciero;

class FinanzaController extends Controller
{
    public function index()
    {
        $transacciones = TransaccionFinanciera::query()
            ->leftJoin('METODO_PAGO as mp', 'TRANSACCION_FINANCIERA.ID_METODO_PAGO', '=', 'mp.ID_METODO_PAGO')
            ->select('TRANSACCION_FINANCIERA.*', 'mp.NOMBRE as METODO_PAGO_NOMBRE', 'mp.TIPO as METODO_PAGO_TIPO')
            ->orderBy('TRANSACCION_FINANCIERA.ID_TRANSACCION', 'desc')
            ->get();

        return view('finanzas.index', compact('transacciones'));
    }

    public function show($id)
    {
        $transaccion = TransaccionFinanciera::query()
            ->leftJoin('METODO_PAGO as mp', 'TRANSACCION_FINANCIERA.ID_METODO_PAGO', '=', 'mp.ID_METODO_PAGO')
            ->select('TRANSACCION_FINANCIERA.*', 'mp.NOMBRE as METODO_PAGO_NOMBRE', 'mp.TIPO as METODO_PAGO_TIPO')
            ->where('TRANSACCION_FINANCIERA.ID_TRANSACCION', $id)
            ->firstOrFail();

        return view('finanzas.show', compact('transaccion'));
    }

    public function detalles($id)
    {
        $transaccion = TransaccionFinanciera::findOrFail($id);

        $detalles = $transaccion->id_venta === null
            ? collect()
            : DetalleFinanciero::where('id_venta', $transaccion->id_venta)->get();

        return view('finanzas.detalles', compact('detalles', 'transaccion'));
    }
}
