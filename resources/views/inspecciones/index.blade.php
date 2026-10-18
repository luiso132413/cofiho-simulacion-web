
@extends('layouts.app')

@section('title', 'Inspecciones - COFIHO V2')

@section('page-title', 'Gestión de Inspecciones')

@section('content')

<div class="card">

    <h2>Listado de Inspecciones</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID Inspección</th>
                    <th>ID Vehículo</th>
                    <th>VIN</th>
                    <th>ID Usuario</th>
                    <th>Fecha</th>
                    <th>Resultado</th>
                    <th>Observaciones</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($inspecciones as $inspeccion)
                    <tr>
                        <td>
                            {{ $inspeccion->id_inspeccion }}
                        </td>

                        <td>
                            {{ $inspeccion->id_vehiculo }}
                        </td>

                        <td>
                            {{ $inspeccion->vehiculo->vin ?? 'No disponible' }}
                        </td>

                        <td>
                            {{ $inspeccion->id_usuario ?? 'No asignado' }}
                        </td>

                        <td>
                            {{ $inspeccion->fecha_inspeccion ?? 'No registrada' }}
                        </td>

                        <td>
                            {{ $inspeccion->resultado ?? 'PENDIENTE' }}
                        </td>

                        <td>
                            {{ $inspeccion->observaciones ?? 'Sin observaciones' }}
                        </td>

                        <td>
                            <a href="{{ route('inspecciones.show', $inspeccion->id_inspeccion) }}"
                               class="btn">
                                Ver detalles
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center; padding:20px;">
                            No hay inspecciones registradas.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
