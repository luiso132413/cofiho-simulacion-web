<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Services\ReservaService;
use Illuminate\Http\Request;
use Throwable;

class ReservaController extends Controller
{
    public function index()
    {
        $reservas = Reserva::orderBy('id_reserva', 'desc')
            ->get();

        return view('reservas.index', compact('reservas'));
    }

    public function show($id)
    {
        $reserva = Reserva::findOrFail($id);

        return view('reservas.show', compact('reserva'));
    }

    public function store(Request $request, ReservaService $reservaService)
    {
        $datos = $request->validate([
            'id_cliente' => 'required|integer|min:1',
            'id_vehiculo' => 'required|integer|min:1',
            'id_usuario' => 'nullable|integer|min:1',
            'canal' => 'required|string|max:50',
        ]);

        try {
            $idReserva = $reservaService->crearReserva(
                (int) $datos['id_cliente'],
                (int) $datos['id_vehiculo'],
                isset($datos['id_usuario'])
                    ? (int) $datos['id_usuario']
                    : null,
                $datos['canal']
            );

            return redirect()
                ->route('reservas.index')
                ->with('success', "Reserva {$idReserva} creada correctamente.");
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'reserva' => 'No fue posible crear la reserva. Verifica los datos e inténtalo nuevamente.',
                ]);
        }
    }

    public function storeFIFO(Request $request, ReservaService $reservaService)
    {
        $datos = $request->validate([
            'id_predio' => 'required|integer|min:1',
            'id_cliente' => 'required|integer|min:1',
            'id_usuario' => 'nullable|integer|min:1',
            'canal' => 'required|string|max:50',
        ]);

        try {
            $resultado = $reservaService->reservarFIFO(
                (int) $datos['id_predio'],
                (int) $datos['id_cliente'],
                isset($datos['id_usuario'])
                    ? (int) $datos['id_usuario']
                    : null,
                $datos['canal']
            );

            return redirect()
                ->route('reservas.index')
                ->with(
                    'success',
                    "Reserva {$resultado['id_reserva']} creada para el vehículo {$resultado['id_vehiculo']} mediante FIFO."
                );
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'reserva' => 'No fue posible reservar mediante FIFO. Verifica los datos y la disponibilidad del inventario.',
                ]);
        }
    }
}
