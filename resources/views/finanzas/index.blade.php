
@extends('layouts.app')

@section('title', 'Finanzas - COFIHO V2')

@section('page-title', 'Gestión Financiera')

@section('content')

<div class="card">

    <h2>Transacciones Financieras</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID Transacción</th>
                    <th>ID Venta</th>
                    <th>ID Reserva</th>
                    <th>Método de Pago</th>
                    <th>Tipo</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transacciones as $transaccion)
                    <tr>
                        <td>{{ $transaccion->id_transaccion }}</td>

                        <td>{{ $transaccion->id_venta ?? 'N/A' }}</td>

                        <td>{{ $transaccion->id_reserva ?? 'N/A' }}</td>

                        <td>{{ $transaccion->id_metodo_pago }}</td>

                        <td>{{ $transaccion->tipo }}</td>

                        <td>
                            Q {{ number_format((float) $transaccion->monto, 2) }}
                        </td>

                        <td>{{ $transaccion->estado }}</td>

                        <td>
                            {{ $transaccion->fecha_transaccion ?? 'No registrada' }}
                        </td>

                        <td>
                            <a href="{{ route('finanzas.show', $transaccion->id_transaccion) }}"
                               class="btn">
                                Ver detalles
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center;">
                            No hay transacciones financieras registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
