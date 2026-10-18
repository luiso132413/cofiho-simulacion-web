
@extends('layouts.app')

@section('title', 'Antigüedad del Inventario - COFIHO V2')

@section('page-title', 'Reporte de Antigüedad del Inventario')

@section('content')

<div class="card" style="margin-bottom:20px;">

    <h2>Antigüedad de Vehículos en Inventario</h2>

    <p style="color:#64748b; margin-bottom:20px;">
        Consulta los días de permanencia de cada vehículo
        en inventario, su valor y su clasificación
        según el rango de antigüedad.
    </p>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID Predio</th>
                    <th>ID Vehículo</th>
                    <th>VIN</th>
                    <th>Fecha de Ingreso</th>
                    <th>Días en Inventario</th>
                    <th>Precio de Lista</th>
                    <th>Rango de Antigüedad</th>
                </tr>
            </thead>

            <tbody>
                @forelse($inventario->sortByDesc('dias_inventario') as $vehiculo)
                    <tr>
                        <td>{{ $vehiculo->id_predio }}</td>

                        <td>{{ $vehiculo->id_vehiculo }}</td>

                        <td>{{ $vehiculo->vin }}</td>

                        <td>
                            {{ $vehiculo->fecha_ingreso ?? 'No registrada' }}
                        </td>

                        <td>
                            {{ $vehiculo->dias_inventario }}
                        </td>

                        <td>
                            Q {{ number_format((float) $vehiculo->precio_lista, 2) }}
                        </td>

                        <td>
                            {{ $vehiculo->rango_antiguedad }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:20px;">
                            No hay vehículos registrados
                            en el reporte de antigüedad.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<div style="margin-top:15px;">
    <a href="{{ route('reportes.index') }}" class="btn">
        Volver a Reportes
    </a>
</div>

@endsection
