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

    $predios = DB::connection('oracle')
        ->table('PREDIO')
        ->select('ID_PREDIO', 'NOMBRE')
        ->get()
        ->keyBy('id_predio');

    $resumenPredios = $inventario
        ->groupBy('id_predio')
        ->map(function ($vehiculos, $idPredio) use ($predios) {
            return [
                'predio' => $predios->get($idPredio)->nombre
                    ?? 'Predio ' . $idPredio,
                'dias_0_30' => $vehiculos->where('rango_antiguedad', '0-30 DIAS')->count(),
                'dias_31_60' => $vehiculos->where('rango_antiguedad', '31-60 DIAS')->count(),
                'dias_61_90' => $vehiculos->where('rango_antiguedad', '61-90 DIAS')->count(),
                'dias_mas_90' => $vehiculos->where('rango_antiguedad', 'MAS DE 90 DIAS')->count(),
                'total' => $vehiculos->count(),
                'valor' => $vehiculos->sum('precio_lista'),
            ];
        })
        ->values();

    return view(
        'reportes.antiguedad_inventario',
        compact('inventario', 'resumenPredios')
    );
}

}
