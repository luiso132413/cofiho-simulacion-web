
@extends('layouts.app')

@section('title', 'Inventario - COFIHO V2')
@section('page-title', 'Gestión de Inventario')

@section('content')

<div class="card">

    <h2>Inventario de Vehículos</h2>

    <p>
        Monitoreo de vehículos registrados por predio,
        incluyendo su estado actual y las fechas de
        ingreso y salida.
    </p>

    <div class="table-responsive">

        <table
            data-filtrable
            data-filtros="Predio,Estado,Registro"
        >
            <thead>
                <tr>
                    <th>ID Inventario</th>
                    <th>Vehículo</th>
                    <th>Predio</th>
                    <th>Fecha de Ingreso</th>
                    <th>Fecha de Salida</th>
                    <th>Estado</th>
                    <th>Registro</th>
                </tr>
            </thead>

            <tbody>

                @forelse($inventarios as $inventario)

                    @php
                        $estadoVehiculo = strtoupper(
                            trim((string) (
                                $inventario->vehiculo?->estado ?? ''
                            ))
                        );

                        $tieneSalida = !is_null(
                            $inventario->fecha_salida
                        );

                        $estadoMostrado = $estadoVehiculo !== ''
                            ? $estadoVehiculo
                            : 'SIN ESTADO';

                        $registro = $tieneSalida
                            ? 'HISTÓRICO'
                            : 'VIGENTE';
                    @endphp

                    <tr>

                        <td>
                            {{ $inventario->id_inventario }}
                        </td>

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
                            <x-estado :valor="$estadoMostrado" />
                        </td>

                        <td>
                            @if($tieneSalida)
                                <span class="badge badge-neutral">
                                    HISTÓRICO
                                </span>
                            @else
                                <span class="badge badge-info">
                                    VIGENTE
                                </span>
                            @endif
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7" style="text-align:center;">
                            No hay registros de inventario.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>

    </div>

</div>

@endsection
