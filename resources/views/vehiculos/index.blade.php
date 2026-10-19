
@extends('layouts.app')

@section('title', 'Vehículos - COFIHO V2')

@section('page-title', 'Gestión de Vehículos')

@section('content')

<div class="card">

    <h2>Listado de Vehículos</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>VIN</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Año</th>
                    <th>Precio</th>
                    <th>Estado</th>
                </tr>
            </thead>

            <tbody>
                <form action="{{ route('logout') }}" method="POST" style="text-align: right; padding: 15px;">
    @csrf
    <button type="submit" style="padding: 10px 20px; cursor: pointer;">
        Cerrar sesión
    </button>
</form>
                @forelse($vehiculos as $vehiculo)
                    <tr>
                        <td>{{ $vehiculo->id_vehiculo }}</td>

                        <td>{{ $vehiculo->vin }}</td>

                        <td>
                            {{ $vehiculo->modelo?->marca?->nombre ?? 'Sin marca' }}
                        </td>

                        <td>
                            {{ $vehiculo->modelo?->nombre ?? 'Sin modelo' }}
                        </td>

                        <td>{{ $vehiculo->anio }}</td>

                        <td>
                            Q {{ number_format((float) ($vehiculo->precio_lista ?? 0), 2) }}
                        </td>

                        <td>{{ $vehiculo->estado }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;">
                            No hay vehículos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
