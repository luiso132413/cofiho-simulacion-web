
@extends('layouts.app')

@section('title', 'Detalle de Venta - COFIHO V2')
@section('page-title', 'Detalle de Venta')

@section('content')

@php
    $total = (float) ($venta->total ?? 0);

    $enganche = $venta->reserva
        ? (float) $venta->reserva->transacciones()
            ->where('tipo', 'ENGANCHE')
            ->where('estado', 'APROBADA')
            ->sum('monto')
        : 0;

    $devoluciones = $venta->reserva
        ? (float) $venta->reserva->transacciones()
            ->where('tipo', 'DEVOLUCION')
            ->where('estado', 'APROBADA')
            ->sum('monto')
        : 0;

    $engancheNeto = $enganche - $devoluciones;

    $saldoPagado = (float) $venta->transacciones
        ->where('tipo', 'PAGO')
        ->where('estado', 'APROBADA')
        ->sum('monto');

    $gastos = (float) $venta->detallesFinancieros
        ->whereIn('concepto', ['TITULACION', 'PLACAS', 'TRASPASO'])
        ->sum('monto');

    $ingresoNeto = $total - $gastos;
@endphp

<div class="card" style="margin-bottom:20px;">
    <h2>Información de la Venta</h2>

    <div class="table-responsive">
        <table>
            <tbody>
                <tr>
                    <th>ID Venta</th>
                    <td>{{ $venta->id_venta }}</td>
                </tr>
                <tr>
                    <th>ID Cliente</th>
                    <td>{{ $venta->id_cliente }}</td>
                </tr>
                <tr>
                    <th>ID Usuario</th>
                    <td>{{ $venta->id_usuario ?? 'No asignado' }}</td>
                </tr>
                <tr>
                    <th>Fecha</th>
                    <td>{{ $venta->fecha_venta ?? 'No registrada' }}</td>
                </tr>
                <tr>
                    <th>Estado</th>
                    <td>{{ $venta->estado ?? 'No definido' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="margin-bottom:20px;">
    <h2>Desglose Financiero - RF-05</h2>

    <div class="table-responsive">
        <table>
            <tbody>
                <tr>
                    <th>Precio del vehículo</th>
                    <td>Q {{ number_format($total, 2) }}</td>
                </tr>
                <tr>
                    <th>Enganche aprobado</th>
                    <td>Q {{ number_format($enganche, 2) }}</td>
                </tr>
                <tr>
                    <th>Devoluciones aprobadas</th>
                    <td>Q {{ number_format($devoluciones, 2) }}</td>
                </tr>
                <tr>
                    <th>Enganche neto aplicado</th>
                    <td>Q {{ number_format($engancheNeto, 2) }}</td>
                </tr>
                <tr>
                    <th>Saldo pagado</th>
                    <td>Q {{ number_format($saldoPagado, 2) }}</td>
                </tr>
                <tr>
                    <th>Gastos asumidos por COFIHO</th>
                    <td>Q {{ number_format($gastos, 2) }}</td>
                </tr>
                <tr>
                    <th>Ingreso neto estimado después de gastos</th>
                    <td>
                        <strong>Q {{ number_format($ingresoNeto, 2) }}</strong>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="margin-bottom:20px;">
    <h2>Detalle de Gastos</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Concepto</th>
                    <th>Descripción</th>
                    <th>Monto</th>
                </tr>
            </thead>
            <tbody>
                @forelse($venta->detallesFinancieros as $detalle)
                    <tr>
                        <td>{{ $detalle->concepto }}</td>
                        <td>{{ $detalle->descripcion ?? '-' }}</td>
                        <td>
                            Q {{ number_format((float) $detalle->monto, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align:center;">
                            No hay gastos financieros registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="card" style="margin-bottom:20px;">
    <h2>Vehículos de la Venta</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID Detalle</th>
                    <th>ID Vehículo</th>
                    <th>Cantidad</th>
                    <th>Precio Lista</th>
                    <th>Precio Venta</th>
                </tr>
            </thead>
            <tbody>
                @forelse($venta->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->id_detalle_venta }}</td>
                        <td>{{ $detalle->id_vehiculo }}</td>
                        <td>{{ $detalle->cantidad ?? 1 }}</td>
                        <td>
                            Q {{ number_format((float) $detalle->precio_lista, 2) }}
                        </td>
                        <td>
                            Q {{ number_format((float) $detalle->precio_venta, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;">
                            No hay vehículos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<a href="{{ route('ventas.index') }}" class="btn">
    Volver a Ventas
</a>

@endsection
