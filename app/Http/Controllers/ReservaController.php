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

    $modelos = \Illuminate\Support\Facades\DB::connection('oracle')
        ->table('MODELO')
        ->select('ID_MODELO')
        ->orderBy('ID_MODELO')
        ->get();

    return view('reservas.index', compact('reservas', 'modelos'));
}

    public function show($id)
    {
        $reserva = Reserva::findOrFail($id);

        $totales = \Illuminate\Support\Facades\DB::connection('oracle')
            ->table('TRANSACCION_FINANCIERA')
            ->where('ID_RESERVA', $id)
            ->selectRaw("SUM(CASE WHEN TIPO = 'ENGANCHE' AND ESTADO = 'APROBADA' THEN MONTO ELSE 0 END) AS TOTAL_ENGANCHES")
            ->selectRaw("SUM(CASE WHEN TIPO = 'DEVOLUCION' AND ESTADO IN ('PENDIENTE', 'APROBADA') THEN MONTO ELSE 0 END) AS TOTAL_DEVOLUCIONES")
            ->first();

        $saldoDevolucion = max(0, round(
            (float) ($totales->total_enganches ?? 0)
            - (float) ($totales->total_devoluciones ?? 0), 2
        ));

        return view('reservas.show', compact('reserva', 'saldoDevolucion'));
    }

    public function store(Request $request, ReservaService $reservaService)
    {
        $datos = $request->validate([
            'id_cliente' => 'required|integer|min:1',
            'id_vehiculo' => 'required|integer|min:1',
            
            'canal' => 'required|string|max:50',
        ]);

        try {
            $idReserva = $reservaService->crearReserva(
                (int) $datos['id_cliente'],
                (int) $datos['id_vehiculo'],
                (int) $request->user()->id_usuario,
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
            'id_modelo' => 'required|integer|min:1',
            'anio' => 'required|integer|min:1900|max:2100',
            'color' => 'required|string|max:50',
            
            'canal' => 'required|string|max:50',
        ]);

        try {
            $resultado = $reservaService->reservarFIFO(
                (int) $datos['id_predio'],
                (int) $datos['id_cliente'],
                (int) $request->user()->id_usuario,
                $datos['canal'],
                (int) $datos['id_modelo'],
                (int) $datos['anio'],
                trim($datos['color'])
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

    public function registrarEnganche(
    Request $request,
    ReservaService $reservaService,
    $id
) {
    $datos = $request->validate([
        'id_metodo_pago' => 'required|integer|min:1',
        'monto' => 'required|numeric|gt:0',
        'referencia' => 'required|string|max:100',
    ]);

    try {
        $reservaService->registrarEnganche(
            (int) $id,
            (int) $datos['id_metodo_pago'],
            (float) $datos['monto'],
            trim($datos['referencia'])
        );

        return redirect()
            ->route('reservas.show', $id)
            ->with('success', 'Enganche registrado correctamente.');

    } catch (Throwable $e) {
        report($e);

        return back()
            ->withInput()
            ->withErrors([
                'enganche' => 'No fue posible registrar el enganche. Verifica que la reserva esté vigente y que la referencia no esté duplicada.',
            ]);
    }
}

    public function registrarDevolucion(
        Request $request,
        ReservaService $reservaService,
        $id
    ) {
        $datos = $request->validate([
            'id_metodo_pago' => 'required|integer|min:1',
            'monto' => 'required|numeric|gt:0',
            'referencia' => 'required|string|max:120',
        ]);

        try {
            $reservaService->registrarDevolucion(
                (int) $id,
                (int) $datos['id_metodo_pago'],
                (float) $datos['monto'],
                trim($datos['referencia'])
            );

            return redirect()
                ->route('reservas.show', $id)
                ->with('success', 'Devolución registrada correctamente.');
        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'devolucion' => 'No fue posible registrar la devolución. Verifica el estado de la reserva, el saldo disponible y la referencia.',
                ]);
        }
    }

    
public function checkout(Request $request, ReservaService $reservaService)
{
    $datos = $request->validate([
        'id_cliente' => 'required|integer|min:1',
        'id_vehiculo' => 'required|integer|min:1',
        
        'canal' => 'required|string|max:50',
        'id_metodo_pago' => 'required|integer|min:1',
        'monto_enganche' => 'required|numeric|gt:0',
        'referencia' => 'required|string|max:120',
    ]);

    try {
        $resultado = $reservaService->checkoutReserva(
            (int) $datos['id_cliente'],
            (int) $datos['id_vehiculo'],
            (int) $request->user()->id_usuario,
            $datos['canal'],
            (int) $datos['id_metodo_pago'],
            (float) $datos['monto_enganche'],
            trim($datos['referencia'])
        );

        return redirect()
            ->route('reservas.show', $resultado['id_reserva'])
            ->with(
                'success',
                "Reserva {$resultado['id_reserva']} creada. " .
                "Enganche registrado y gastos calculados: Q" .
                number_format($resultado['gastos'], 2)
            );
    } catch (Throwable $e) {
        report($e);

        return back()
            ->withInput()
            ->withErrors([
                'checkout' => 'No fue posible completar el checkout. Verifica los datos y vuelve a intentarlo.',
            ]);
    }
}


}
