
@extends('layouts.app')

@section('title', 'Inventario - COFIHO V2')

@section('page-title', 'Gestión de Inventario')

@section('content')

<div class="card">

    <h2>Inventario de Vehículos</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID Inventario</th>
                    <th>Vehículo</th>
                    <th>Predio</th>
                    <th>Fecha de Ingreso</th>
                    <th>Fecha de Salida</th>
                    <th>Estado</th>
                </tr>
            </thead>

            <tbody>
                @forelse($inventarios as $inventario)
                    <tr>
                        <td>{{ $inventario->id_inventario }}</td>

                        <td>
                            {{ $inventario->vehiculo?->vin ?? 'Sin vehículo' }}
                        </td>

                        <td>
                            {{ $inventario->predio?->nombre ?? 'Sin predio' }}
                        </td>

                        <td>
                            {{ $inventario->fecha_ingreso ?? 'No registrada' }}
                        </td>

                        <td>
                            {{ $inventario->fecha_salida ?? 'Sin salida' }}
                        </td>

                        <td>
                            {{ $inventario->estado }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;">
                            No hay registros de inventario.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
