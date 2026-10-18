
@extends('layouts.app')

@section('title', 'Detalle de Reserva - COFIHO V2')

@section('page-title', 'Detalle de Reserva')

@section('content')

<div class="card">

    <h2>Información de la Reserva</h2>

    <div class="table-responsive">
        <table>
            <tbody>
                <tr>
                    <th>ID Reserva</th>
                    <td>{{ $reserva->id_reserva }}</td>
                </tr>

                <tr>
                    <th>ID Cliente</th>
                    <td>{{ $reserva->id_cliente }}</td>
                </tr>

                <tr>
                    <th>ID Vehículo</th>
                    <td>{{ $reserva->id_vehiculo }}</td>
                </tr>

                <tr>
                    <th>Fecha de Reserva</th>
                    <td>{{ $reserva->fecha_reserva ?? 'No registrada' }}</td>
                </tr>

                <tr>
                    <th>Estado</th>
                    <td>{{ $reserva->estado ?? 'No definido' }}</td>
                </tr>

                <tr>
                    <th>Canal</th>
                    <td>{{ $reserva->canal ?? 'No registrado' }}</td>
                </tr>

                <tr>
                    <th>ID Usuario</th>
                    <td>{{ $reserva->id_usuario ?? 'No asignado' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 24px;">
        <a href="{{ route('reservas.index') }}" class="btn">
            Volver a Reservas
        </a>
    </div>

</div>

@endsection
