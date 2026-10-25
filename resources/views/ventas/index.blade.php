
@extends('layouts.app')

@section('title', 'Ventas - COFIHO V2')

@section('page-title', 'Gestión de Ventas')

@section('content')

<div class="card" style="margin-bottom:20px;">

    <h2>Procesar Nueva Venta</h2>

    @if(session('success'))
        <div style="padding:12px; background:#dcfce7; color:#166534; margin-bottom:15px; border-radius:6px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="padding:12px; background:#fee2e2; color:#991b1b; margin-bottom:15px; border-radius:6px;">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('ventas.store') }}" method="POST">
        @csrf

        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:15px;">

            <div>
                <label for="id_cliente">Cliente</label>

                <select
                    id="id_cliente"
                    name="id_cliente"
                    required
                    style="width:100%; padding:10px; margin-top:5px;"
                >
                    <option value="">Seleccione un cliente</option>

                    @foreach($clientes as $cliente)
                        <option
                            value="{{ $cliente->id_cliente }}"
                            {{ old('id_cliente') == $cliente->id_cliente ? 'selected' : '' }}
                        >
                            {{ $cliente->id_cliente }} -
                            {{ $cliente->nombre }}
                            {{ $cliente->apellido }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div>
                <label for="id_vehiculo">ID Vehículo</label>
                <input type="number"
                       id="id_vehiculo"
                       name="id_vehiculo"
                       value="{{ old('id_vehiculo') }}"
                       min="1"
                       required
                       style="width:100%; padding:10px; margin-top:5px;">
            </div>

            <div>
                <label for="id_metodo_pago">ID Método de Pago</label>
                <input type="number"
                       id="id_metodo_pago"
                       name="id_metodo_pago"
                       value="{{ old('id_metodo_pago') }}"
                       min="1"
                       required
                       style="width:100%; padding:10px; margin-top:5px;">
            </div>

            <div>
                <label for="id_reserva">ID Reserva (opcional)</label>
                <input type="number"
                       id="id_reserva"
                       name="id_reserva"
                       value="{{ old('id_reserva') }}"
                       min="1"
                       style="width:100%; padding:10px; margin-top:5px;">
            </div>

        </div>

        <div style="margin-top:20px;">
            <button type="submit"
                    class="btn"
                    onclick="return confirm('¿Deseas procesar esta venta? Esta operación registrará cambios en Oracle.');">
                Procesar Venta
            </button>
        </div>

    </form>

</div>

<div class="card">

    <h2>Listado de Ventas</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>ID Cliente</th>
                    <th>ID Usuario</th>
                    <th>Fecha</th>
                    <th>Total</th>
                    <th>Estado</th>
                    <th>Detalle</th>
                </tr>
            </thead>

            <tbody>
                @forelse($ventas as $venta)
                    <tr>
                        <td>{{ $venta->id_venta }}</td>
                        <td>{{ $venta->id_cliente }}</td>
                        <td>{{ $venta->id_usuario ?? 'No asignado' }}</td>
                        <td>{{ $venta->fecha_venta ?? 'No registrada' }}</td>

                        <td>
                            {{ $venta->total !== null
                                ? 'Q ' . number_format((float) $venta->total, 2)
                                : 'No disponible' }}
                        </td>

                        <td>{{ $venta->estado ?? 'No definido' }}</td>

                        <td>
                            <a href="{{ route('ventas.show', $venta->id_venta) }}"
                               class="btn">
                                Ver
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;">
                            No hay ventas registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
