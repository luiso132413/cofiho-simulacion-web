
@extends('layouts.app')

@section('title', 'Detalles Financieros - COFIHO V2')

@section('page-title', 'Detalles Financieros')

@section('content')

<div class="card">

    <h2>Desglose Financiero de la Venta</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID Detalle</th>
                    <th>ID Venta</th>
                    <th>Concepto</th>
                    <th>Descripción</th>
                    <th>Monto</th>
                </tr>
            </thead>

            <tbody>
                @forelse($detalles as $detalle)
                    <tr>
                        <td>
                            {{ $detalle->id_detalle_fin }}
                        </td>

                        <td>
                            {{ $detalle->id_venta }}
                        </td>

                        <td>
                            {{ $detalle->concepto ?? 'No definido' }}
                        </td>

                        <td>
                            {{ $detalle->descripcion ?? 'Sin descripción' }}
                        </td>

                        <td>
                            Q {{ number_format((float) $detalle->monto, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; padding:20px;">
                            No hay detalles financieros registrados
                            para esta venta.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<div style="margin-top:15px; display:flex; gap:10px;">

    <a href="{{ url()->previous() }}" class="btn">
        Volver a la Transacción
    </a>

    <a href="{{ route('finanzas.index') }}" class="btn">
        Volver a Finanzas
    </a>

</div>

@endsection
