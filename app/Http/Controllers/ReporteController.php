<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }


    public function inventario(\Illuminate\Http\Request $request)
    {
        $vista = $request->query('vista', 'fifo');
        if (!in_array($vista, ['fifo', 'antiguedad'], true)) {
            $vista = 'fifo';
        }
        if ($vista === 'fifo') {
            $vehiculos = DB::connection('oracle')->table('V_INVENTARIO_FIFO')->get();
            return view('reportes.inventario', compact('vista', 'vehiculos'));
        }
        $inventario = DB::connection('oracle')->table('V_REPORTE_ANTIGUEDAD')->get();
        $predios = DB::connection('oracle')->table('PREDIO')->select('ID_PREDIO', 'NOMBRE')->get()->keyBy('id_predio');
        $resumenPredios = $inventario->groupBy('id_predio')->map(function ($vehiculos, $idPredio) use ($predios) {
            return [
                'predio' => $predios->get($idPredio)->nombre ?? 'Predio ' . $idPredio,
                'dias_0_30' => $vehiculos->where('rango_antiguedad', '0-30 DIAS')->count(),
                'dias_31_60' => $vehiculos->where('rango_antiguedad', '31-60 DIAS')->count(),
                'dias_61_90' => $vehiculos->where('rango_antiguedad', '61-90 DIAS')->count(),
                'dias_mas_90' => $vehiculos->where('rango_antiguedad', 'MAS DE 90 DIAS')->count(),
                'total' => $vehiculos->count(),
                'valor' => $vehiculos->sum('precio_lista'),
            ];
        })->values();
        return view('reportes.inventario', compact('vista', 'inventario', 'resumenPredios'));
    }

    public function ventas(\Illuminate\Http\Request $request)
    {
        $datos = $request->validate([
            'desde' => ['nullable', 'date_format:Y-m-d'],
            'hasta' => ['nullable', 'date_format:Y-m-d', 'after_or_equal:desde'],
            'estado' => ['nullable', 'in:TODOS,CONFIRMADA'],
        ]);
        $desde = $datos['desde'] ?? '';
        $hasta = $datos['hasta'] ?? '';
        $estado = $datos['estado'] ?? 'CONFIRMADA';
        $consulta = DB::connection('oracle')->table('VENTA')
            ->select('ID_VENTA', 'ID_CLIENTE', 'ID_USUARIO', 'FECHA_VENTA', 'ESTADO', 'TOTAL');
        if ($desde !== '') {
            $consulta->whereRaw("FECHA_VENTA >= TO_DATE(?, 'YYYY-MM-DD')", [$desde]);
        }
        if ($hasta !== '') {
            $consulta->whereRaw("FECHA_VENTA < TO_DATE(?, 'YYYY-MM-DD') + 1", [$hasta]);
        }
        if ($estado !== 'TODOS') {
            $consulta->where('ESTADO', $estado);
        }
        $ventas = $consulta->orderBy('ID_VENTA', 'desc')->get();
        $porVendedor = $ventas->groupBy('id_usuario')->sortKeys();
        $meses = $ventas->groupBy(function ($venta) {
            return \Carbon\Carbon::parse($venta->fecha_venta)->format('Y-m');
        })->map(fn ($grupo) => round((float) $grupo->sum('total'), 2))->sortKeys();
        return view('reportes.ventas', compact('ventas', 'porVendedor', 'meses', 'desde', 'hasta', 'estado'));
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
