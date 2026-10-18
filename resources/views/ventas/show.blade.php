
@extends('layouts.app')

@section('title', 'Detalle de Venta - COFIHO V2')

@section('page-title', 'Detalle de Venta')

@section('content')

<div class="card">

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
                    <th>Fecha de Venta</th>
                    <td>{{ $venta->fecha_venta ?? 'No registrada' }}</td>
                </tr>

                <tr>
                    <th>Total</th>
                    <td>
                        Q {{ number_format((float) ($venta->total ?? 0), 2) }}
                    </td>
                </tr>

                <tr>
                    <th>Estado</th>
                    <td>{{ $venta->estado ?? 'No definido' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<div class="card">

    <h2>Detalles de la Venta</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID Detalle</th>
                    <th>ID Vehículo</th>
                    <th>Cantidad</th>
                    <th>Precio Unitario</th>
                    <th>Subtotal</th>
                </tr>
            </thead>

            <tbody>
                @forelse($venta->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->id_detalle_venta }}</td>

                        <td>{{ $detalle->id_vehiculo }}</td>

                        <td>{{ $detalle->cantidad ?? 1 }}</td>

                        <td>Q {{ number_format((float) $detalle->precio_lista, 2) }}</td>
<td>Q {{ number_format((float) $detalle->precio_venta, 2) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center;">
                            No hay detalles registrados para esta venta.
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
