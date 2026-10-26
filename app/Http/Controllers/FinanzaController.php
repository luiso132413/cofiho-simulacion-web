<?php

namespace App\Http\Controllers;

use App\Models\TransaccionFinanciera;
use App\Models\DetalleFinanciero;
use Illuminate\Support\Facades\DB;

class FinanzaController extends Controller
{
    public function index()
    {
        $transacciones = TransaccionFinanciera::orderBy('id_transaccion', 'desc')->get();
        return view('finanzas.index', compact('transacciones'));
    }

    public function show($id)
    {
        $transaccion = TransaccionFinanciera::findOrFail($id);
        $metodoPago = DB::table('METODO_PAGO')
            ->where('ID_METODO_PAGO', $transaccion->id_metodo_pago)
            ->first(['NOMBRE', 'TIPO', 'ACTIVO']);
        return view('finanzas.show', compact('transaccion', 'metodoPago'));
    }

    public function detalles($id)
    {
        $transaccion = TransaccionFinanciera::findOrFail($id);
        $detalles = $transaccion->id_venta
            ? DetalleFinanciero::where('id_venta', $transaccion->id_venta)->get()
            : collect();
        return view('finanzas.detalles', compact('detalles', 'transaccion'));
    }
}
