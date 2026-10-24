<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Services\VentaService;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\Auth;

class VentaController extends Controller
{
    public function index()
    {
        $ventas = Venta::with([
            'detalles',
        ])
        ->orderBy('id_venta', 'desc')
        ->get();

        return view('ventas.index', compact('ventas'));
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
            'id_cliente' => 'required|integer|min:1',
            'id_vehiculo' => 'required|integer|min:1',
            // 'id_usuario' => 'required|integer|min:1|exists:oracle.USUARIO,ID_USUARIO',
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
