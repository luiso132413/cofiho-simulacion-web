<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use App\Services\ReservaService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Throwable;

class ReservaController extends Controller
{
    public function index()
    {
        $db = \Illuminate\Support\Facades\DB::connection('oracle');
        $reservas = $db->table('RESERVA as R')
            ->join('CLIENTE as C', 'C.ID_CLIENTE', '=', 'R.ID_CLIENTE')
            ->join('VEHICULO as V', 'V.ID_VEHICULO', '=', 'R.ID_VEHICULO')
            ->join('MODELO as MO', 'MO.ID_MODELO', '=', 'V.ID_MODELO')
            ->join('MARCA as MA', 'MA.ID_MARCA', '=', 'MO.ID_MARCA')
            ->select('R.*', 'C.NOMBRE as cliente_nombre', 'C.APELLIDO as cliente_apellido',
                'MA.NOMBRE as vehiculo_marca', 'MO.NOMBRE as vehiculo_modelo',
                'V.PLACA as vehiculo_placa', 'V.ANIO as vehiculo_anio')
            ->orderBy('R.ID_RESERVA', 'desc')
            ->get();

        $modelos = $db->table('MODELO as MO')
            ->join('MARCA as MA', 'MA.ID_MARCA', '=', 'MO.ID_MARCA')
            ->select('MO.ID_MODELO as id_modelo', 'MO.NOMBRE as modelo', 'MA.NOMBRE as marca')
            ->orderBy('MA.NOMBRE')
            ->orderBy('MO.NOMBRE')
            ->get();

        $vehiculosDisponibles = $db->table('VEHICULO as V')
            ->join('MODELO as MO', 'MO.ID_MODELO', '=', 'V.ID_MODELO')
            ->join('MARCA as MA', 'MA.ID_MARCA', '=', 'MO.ID_MARCA')
            ->where('V.ESTADO', 'DISPONIBLE')
            ->select('V.ID_VEHICULO as id_vehiculo', 'V.VIN as vin',
                'V.ANIO as anio', 'V.COLOR as color', 'V.CONDICION as condicion',
                'V.PRECIO_LISTA as precio_lista', 'MO.NOMBRE as modelo',
                'MA.NOMBRE as marca')
            ->orderBy('MA.NOMBRE')
            ->orderBy('MO.NOMBRE')
            ->orderBy('V.ANIO')
            ->get();

        $vehiculosFIFO = $db->table('INVENTARIO_PREDIO as I')
            ->join('VEHICULO as V', 'V.ID_VEHICULO', '=', 'I.ID_VEHICULO')
            ->join('MODELO as MO', 'MO.ID_MODELO', '=', 'V.ID_MODELO')
            ->join('MARCA as MA', 'MA.ID_MARCA', '=', 'MO.ID_MARCA')
            ->whereNull('I.FECHA_SALIDA')
            ->where('V.ESTADO', 'DISPONIBLE')
            ->where('V.CONDICION', 'NUEVO')
            ->select('I.ID_PREDIO as id_predio', 'I.ID_INVENTARIO as id_inventario',
                'I.FECHA_INGRESO as fecha_ingreso', 'V.ID_VEHICULO as id_vehiculo',
                'V.ID_MODELO as id_modelo', 'V.ANIO as anio', 'V.COLOR as color',
                'V.PRECIO_LISTA as precio_lista', 'MO.NOMBRE as modelo', 'MA.NOMBRE as marca')
            ->orderBy('I.FECHA_INGRESO')
            ->orderBy('I.ID_INVENTARIO')
            ->get();

        $clientes = \App\Models\Cliente::where('activo', 1)
            ->orderBy('nombre')
            ->orderBy('apellido')
            ->get();

        return view('reservas.index', compact('reservas', 'modelos', 'clientes', 'vehiculosDisponibles', 'vehiculosFIFO'));
    }

    public function show($id)
    {
        $db = \Illuminate\Support\Facades\DB::connection('oracle');
        $reserva = $db->table('RESERVA as R')
            ->join('CLIENTE as C', 'C.ID_CLIENTE', '=', 'R.ID_CLIENTE')
            ->join('VEHICULO as V', 'V.ID_VEHICULO', '=', 'R.ID_VEHICULO')
            ->join('MODELO as MO', 'MO.ID_MODELO', '=', 'V.ID_MODELO')
            ->join('MARCA as MA', 'MA.ID_MARCA', '=', 'MO.ID_MARCA')
            ->select('R.*', 'C.NOMBRE as cliente_nombre', 'C.APELLIDO as cliente_apellido',
                'MA.NOMBRE as vehiculo_marca', 'MO.NOMBRE as vehiculo_modelo',
                'V.PLACA as vehiculo_placa', 'V.ANIO as vehiculo_anio')
            ->where('R.ID_RESERVA', $id)
            ->first();
        abort_if(!$reserva, 404);

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
            
'id_cliente' => [
    'required',
    'integer',
    Rule::exists('oracle.CLIENTE', 'ID_CLIENTE')
        ->where('ACTIVO', 1),
],

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
            
'id_cliente' => [
    'required',
    'integer',
    Rule::exists('oracle.CLIENTE', 'ID_CLIENTE')
        ->where('ACTIVO', 1),
],

            'id_modelo' => 'required|integer|min:1',
            
            'canal' => 'required|string|max:50',
        ]);

        try {
            // Se vuelve a consultar el primer vehículo elegible al confirmar.
            // El procedimiento PL/SQL conserva el bloqueo y la selección definitiva.
            $primero = \Illuminate\Support\Facades\DB::connection('oracle')
                ->table('INVENTARIO_PREDIO as I')
                ->join('VEHICULO as V', 'V.ID_VEHICULO', '=', 'I.ID_VEHICULO')
                ->where('I.ID_PREDIO', (int) $datos['id_predio'])
                ->where('V.ID_MODELO', (int) $datos['id_modelo'])
                ->whereNull('I.FECHA_SALIDA')
                ->where('V.ESTADO', 'DISPONIBLE')
                ->where('V.CONDICION', 'NUEVO')
                ->orderBy('I.FECHA_INGRESO')
                ->orderBy('I.ID_INVENTARIO')
                ->select('V.ANIO as anio', 'V.COLOR as color')
                ->first();

            if (!$primero) {
                return back()->withInput()->withErrors([
                    'reserva' => 'No se cuenta con vehículos disponibles de este modelo en el predio seleccionado.',
                ]);
            }

            $resultado = $reservaService->reservarFIFO(
                (int) $datos['id_predio'],
                (int) $datos['id_cliente'],
                (int) $request->user()->id_usuario,
                $datos['canal'],
                (int) $datos['id_modelo'],
                (int) $primero->anio,
                trim($primero->color)
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
        
'id_cliente' => [
    'required',
    'integer',
    Rule::exists('oracle.CLIENTE', 'ID_CLIENTE')
        ->where('ACTIVO', 1),
],

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
