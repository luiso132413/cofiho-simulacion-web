
@extends('layouts.app')

@section('title', 'Detalle Financiero - COFIHO V2')

@section('page-title', 'Detalle de Transacción Financiera')

@section('content')

<div class="card">

    <h2>Información de la Transacción</h2>

    <div class="table-responsive">
        <table>
            <tbody>
                <tr>
                    <th>ID Transacción</th>
                    <td>{{ $transaccion->id_transaccion }}</td>
                </tr>

                <tr>
                    <th>ID Venta</th>
                    <td>{{ $transaccion->id_venta ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>ID Reserva</th>
                    <td>{{ $transaccion->id_reserva ?? 'N/A' }}</td>
                </tr>

                <tr>
                    <th>ID Método de Pago</th>
                    <td>{{ $transaccion->id_metodo_pago }}</td>
                </tr>

                <tr>
                    <th>Tipo de Transacción</th>
                    <td>{{ $transaccion->tipo }}</td>
                </tr>

                <tr>
                    <th>Monto</th>
                    <td>
                        Q {{ number_format((float) $transaccion->monto, 2) }}
                    </td>
                </tr>

                <tr>
                    <th>Moneda</th>
                    <td>{{ $transaccion->moneda }}</td>
                </tr>

                <tr>
                    <th>Estado</th>
                    <td>{{ $transaccion->estado }}</td>
                </tr>

                <tr>
                    <th>Referencia Externa</th>
                    <td>{{ $transaccion->referencia_externa ?? 'No registrada' }}</td>
                </tr>

                <tr>
                    <th>Fecha de Transacción</th>
                    <td>{{ $transaccion->fecha_transaccion ?? 'No registrada' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<div style="margin-top:15px; display:flex; gap:10px; flex-wrap:wrap;">

    <a href="{{ route('finanzas.detalles', $transaccion->id_transaccion) }}"
       class="btn">
        Ver Detalles Financieros
    </a>

    <a href="{{ route('finanzas.index') }}" class="btn">
        Volver a Finanzas
    </a>

</div>

@endsection
