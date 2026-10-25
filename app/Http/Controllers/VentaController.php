<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Cliente;
use App\Services\VentaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Throwable;

class VentaController extends Controller
{
    public function index()
    {
        // Obtener todas las ventas, incluyendo las de clientes inactivos.
        $ventas = Venta::with([
            'detalles',
        ])
        ->orderBy('id_venta', 'desc')
        ->get();

        // Obtener únicamente clientes activos para nuevas ventas.
        $clientes = Cliente::where('activo', 1)
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->get();

        return view('ventas.index', compact('ventas', 'clientes'));
    }

    public function show($id)
    {
        $venta = Venta::with([
            'detalles',
            'reserva',
            'transacciones',
            'detallesFinancieros',
        ])->findOrFail($id);

        return view('ventas.show', compact('venta'));
    }

    public function store(Request $request, VentaService $ventaService)
    {
        $datos = $request->validate([
            'id_cliente' => [
                'required',
                'integer',
                Rule::exists('oracle.CLIENTE', 'ID_CLIENTE')
                    ->where('ACTIVO', 1),
            ],
            'id_vehiculo' => 'required|integer|min:1',
            'id_metodo_pago' => 'required|integer|min:1',
            'id_reserva' => 'nullable|integer|min:1',
        ]);

        try {
            $idVenta = $ventaService->procesarVenta(
                (int) $datos['id_cliente'],
                (int) $datos['id_vehiculo'],
                (int) Auth::user()->id_usuario,
                (int) $datos['id_metodo_pago'],
                isset($datos['id_reserva'])
                    ? (int) $datos['id_reserva']
                    : null
            );

            return redirect()
                ->route('ventas.index')
                ->with(
                    'success',
                    "Venta {$idVenta} procesada correctamente."
                );

        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'venta' => 'No fue posible procesar la venta. Verifica los datos y el estado del vehículo.',
                ]);
        }
    }
}
