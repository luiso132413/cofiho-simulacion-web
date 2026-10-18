
@extends('layouts.app')

@section('title', 'Reservas - COFIHO V2')

@section('page-title', 'Gestión de Reservas')

@section('content')


<div class="card">
    <h2>Crear Nueva Reserva</h2>

    <form action="{{ route('reservas.store') }}" method="POST">
        @csrf

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:15px;">

            <div>
                <label for="id_cliente">ID Cliente</label>
                <input type="number" id="id_cliente" name="id_cliente"
                       value="{{ old('id_cliente') }}" min="1" required
                       style="width:100%; padding:10px; margin-top:6px;">
            </div>

            <div>
                <label for="id_vehiculo">ID Vehículo</label>
                <input type="number" id="id_vehiculo" name="id_vehiculo"
                       value="{{ old('id_vehiculo') }}" min="1" required
                       style="width:100%; padding:10px; margin-top:6px;">
            </div>

            <div>
                <label for="id_usuario">ID Usuario (opcional)</label>
                <input type="number" id="id_usuario" name="id_usuario"
                       value="{{ old('id_usuario') }}" min="1"
                       style="width:100%; padding:10px; margin-top:6px;">
            </div>

            <div>
                <label for="canal">Canal</label>
                <select id="canal" name="canal" required
                        style="width:100%; padding:10px; margin-top:6px;">
                    <option value="WEB" {{ old('canal', 'WEB') == 'WEB' ? 'selected' : '' }}>WEB</option>
                    <option value="AGENCIA" {{ old('canal') == 'AGENCIA' ? 'selected' : '' }}>AGENCIA</option>
                </select>
            </div>

        </div>

        <div style="margin-top:20px;">
            <button type="submit" class="btn">
                Crear Reserva
            </button>
        </div>
    </form>
</div>


<div class="card">

    <h2>Crear Reserva Automática FIFO</h2>

    <p style="margin-bottom:20px; color:#64748b;">
        El sistema seleccionará automáticamente un vehículo
        según su antigüedad en el inventario del predio.
    </p>

    <form action="{{ route('reservas.fifo') }}" method="POST">
        @csrf

        <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(200px, 1fr)); gap:15px;">

            <div>
                <label for="fifo_predio">Predio</label>
                <select id="fifo_predio" name="id_predio" required
                        style="width:100%; padding:10px; margin-top:6px;">
                    <option value="">Seleccione un predio</option>
                    <option value="1">Cofino Stahl Central Zona 5</option>
                    <option value="2">Agencia Roosevelt</option>
                    <option value="3">Predio Seminuevos Cayala</option>
                </select>
            </div>

            <div>
                <label for="fifo_cliente">ID Cliente</label>
                <input type="number" id="fifo_cliente" name="id_cliente"
                       min="1" required
                       style="width:100%; padding:10px; margin-top:6px;">
            </div>

            <div>
                <label for="fifo_usuario">ID Usuario (opcional)</label>
                <input type="number" id="fifo_usuario" name="id_usuario"
                       min="1"
                       style="width:100%; padding:10px; margin-top:6px;">
            </div>

            <div>
                <label for="fifo_canal">Canal</label>
                <select id="fifo_canal" name="canal" required
                        style="width:100%; padding:10px; margin-top:6px;">
                    <option value="WEB">WEB</option>
                    <option value="AGENCIA">AGENCIA</option>
                </select>
            </div>

        </div>

        <div style="margin-top:20px;">
            <button type="submit" class="btn">
                Reservar mediante FIFO
            </button>
        </div>
    </form>

</div>


<div class="card">

    <h2>Listado de Reservas</h2>

    @if(session('success'))
        <div style="padding: 12px; background: #dcfce7; color: #166534; margin-bottom: 15px; border-radius: 6px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="padding: 12px; background: #fee2e2; color: #991b1b; margin-bottom: 15px; border-radius: 6px;">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID Reserva</th>
                    <th>Cliente</th>
                    <th>Vehículo</th>
                    <th>Fecha Reserva</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($reservas as $reserva)
                    <tr>
                        <td>{{ $reserva->id_reserva }}</td>

                        <td>{{ $reserva->id_cliente }}</td>

                        <td>{{ $reserva->id_vehiculo }}</td>

                        <td>{{ $reserva->fecha_reserva ?? 'No registrada' }}</td>

                        <td>{{ $reserva->estado ?? 'No definido' }}</td>

                        <td>
                            <a href="{{ route('reservas.show', $reserva->id_reserva) }}"
                               class="btn">
                                Ver detalles
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;">
                            No hay reservas registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
