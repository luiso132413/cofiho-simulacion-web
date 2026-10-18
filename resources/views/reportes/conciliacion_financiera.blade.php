
@extends('layouts.app')

@section('title', 'Conciliación Financiera - COFIHO V2')

@section('page-title', 'Reporte de Conciliación Financiera')

@section('content')

<div class="card">

    <h2>Conciliación de Ventas y Pagos</h2>

    <p style="color:#64748b; margin-bottom:20px;">
        Comparación de los importes de ventas con los pagos
        registrados en Oracle, para identificar diferencias
        y verificar el estado de conciliación.
    </p>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Fecha de Venta</th>
                    <th>Estado Venta</th>
                    <th>Total Venta</th>
                    <th>Total Pagado</th>
                    <th>Diferencia</th>
                    <th>Estado Conciliación</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transacciones as $transaccion)
                    <tr>
                        <td>
                            {{ $transaccion->id_venta }}
                        </td>

                        <td>
                            {{ $transaccion->fecha_venta ?? 'No registrada' }}
                        </td>

                        <td>
                            {{ $transaccion->estado_venta }}
                        </td>

                        <td>
                            Q {{ number_format((float) $transaccion->total_venta, 2) }}
                        </td>

                        <td>
                            Q {{ number_format((float) $transaccion->total_pagado, 2) }}
                        </td>

                        <td>
                            Q {{ number_format((float) $transaccion->diferencia, 2) }}
                        </td>

                        <td>
                            {{ $transaccion->estado_conciliacion }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:20px;">
                            No hay registros de conciliación financiera.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<div style="margin-top:15px;">
    <a href="{{ route('reportes.index') }}" class="btn">
        Volver a Reportes
    </a>
</div>

@endsection
