<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    public function inventarioFIFO()
    {
        $vehiculos = DB::connection('oracle')
            ->table('V_INVENTARIO_FIFO')
            ->get();

        return view('reportes.inventario_fifo', compact('vehiculos'));
    }

    public function conciliacionFinanciera()
    {
        $transacciones = DB::connection('oracle')
            ->table('V_CONCILIACION_FINANCIERA')
            ->get();

        return view(
            'reportes.conciliacion_financiera',
            compact('transacciones')
        );
    }

    public function antiguedadInventario()
    {
        $inventario = DB::connection('oracle')
            ->table('V_REPORTE_ANTIGUEDAD')
            ->get();

        return view(
            'reportes.antiguedad_inventario',
            compact('inventario')
        );
    }
}
