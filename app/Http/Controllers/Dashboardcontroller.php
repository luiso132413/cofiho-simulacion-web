<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $rol = auth()->user()?->rol?->nombre;

        if (!in_array($rol, ['ADMINISTRADOR', 'VENDEDOR', 'INSPECTOR'], true)) {
            abort(403, 'No tiene un rol autorizado para acceder al panel.');
        }

        $vehiculos = DB::table('VEHICULO')
            ->selectRaw("COUNT(*) AS TOTAL, SUM(CASE WHEN ESTADO = 'DISPONIBLE' THEN 1 ELSE 0 END) AS DISPONIBLES")
            ->first();

        $datos = [
            'totalVehiculos' => (int) ($vehiculos->total ?? 0),
            'disponibles' => (int) ($vehiculos->disponibles ?? 0),
        ];

        if ($rol === 'ADMINISTRADOR' || $rol === 'VENDEDOR') {
            $datos['ventas'] = DB::table('VENTA')->count();
            $datos['reservasPendientes'] = DB::table('RESERVA')->where('ESTADO', 'PENDIENTE')->count();
            $datos['clientes'] = DB::table('CLIENTE')->count();
        }

        if ($rol === 'ADMINISTRADOR' || $rol === 'INSPECTOR') {
            $datos['inspecciones'] = DB::table('INSPECCION')->count();
            $datos['avaluos'] = DB::table('AVALUO')->count();
        }

        if ($rol === 'ADMINISTRADOR') {
            $datos['usuarios'] = DB::table('USUARIO')->count();
        }

        return view(match ($rol) {
            'ADMINISTRADOR' => 'dashboard.admin',
            'VENDEDOR' => 'dashboard.vendedor',
            'INSPECTOR' => 'dashboard.inspector',
        }, $datos);
    }
}
