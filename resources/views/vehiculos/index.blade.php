@extends('layouts.app')

@section('title', 'Predial - COFIHO V2')

@section('page-title', 'Gestión Predial')

@section('content')

<div class="card">

    <h2>Stock disponible por predio</h2>

    <div class="stats">
        <div class="stat" style="background:#dbeafe;">
            <div class="stat-valor">{{ $stockTotal }}</div>
            <div class="stat-etiqueta">Total de vehículos disponibles</div>
        </div>

        @foreach($stockPorPredio as $predio)
            <div class="stat">
                <div class="stat-valor">{{ $predio['stock'] }}</div>
                <div class="stat-etiqueta">{{ $predio['predio'] }}</div>
            </div>
        @endforeach
    </div>

</div>

<div class="card">

    <h2>Vehículos por predio</h2>

    <div class="table-responsive">
        <table data-filtrable data-filtros="Predio,Marca,Tipo de vehículo,Condición,Estado">
            <thead>
                <tr>
                    <th>ID Predio</th>
                    <th>Predio</th>
                    <th>ID Vehículo</th>
                    <th>VIN</th>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Tipo de vehículo</th>
                    <th>Año</th>
                    <th>Condición</th>
                    <th>Precio</th>
                    <th>Estado</th>
                </tr>
            </thead>

            <tbody>
                @forelse($vehiculos as $vehiculo)
                    <tr>
                        <td>{{ $vehiculo->inventarioActual?->id_predio ?? '—' }}</td>

                        <td>{{ $vehiculo->inventarioActual?->predio?->nombre ?? 'Sin predio' }}</td>

                        <td>{{ $vehiculo->id_vehiculo }}</td>

                        <td>{{ $vehiculo->vin }}</td>

                        <td>{{ $vehiculo->modelo?->marca?->nombre ?? 'Sin marca' }}</td>

                        <td>{{ $vehiculo->modelo?->nombre ?? 'Sin modelo' }}</td>

                        <td>{{ $vehiculo->tipoVehiculo?->nombre ?? 'Sin tipo' }}</td>

                        <td>{{ $vehiculo->anio }}</td>

                        <td>{{ $vehiculo->condicion }}</td>

                        <td>Q {{ number_format((float) ($vehiculo->precio_lista ?? 0), 2) }}</td>

                        <td><x-estado :valor="$vehiculo->estado" /></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" style="text-align:center;">
                            No hay vehículos registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
