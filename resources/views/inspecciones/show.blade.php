
@extends('layouts.app')

@section('title', 'Detalle de Inspección - COFIHO V2')

@section('page-title', 'Detalle de Inspección')

@section('content')

<div class="card" style="margin-bottom:20px;">

    <h2>Información de la Inspección</h2>

    <div class="table-responsive">
        <table>
            <tbody>
                <tr>
                    <th>ID Inspección</th>
                    <td>{{ $inspeccion->id_inspeccion }}</td>
                </tr>

                <tr>
                    <th>ID Vehículo</th>
                    <td>{{ $inspeccion->id_vehiculo }}</td>
                </tr>

                <tr>
                    <th>VIN</th>
                    <td>{{ $inspeccion->vehiculo->vin ?? 'No disponible' }}</td>
                </tr>

                <tr>
                    <th>ID Usuario</th>
                    <td>{{ $inspeccion->id_usuario ?? 'No asignado' }}</td>
                </tr>

                <tr>
                    <th>Fecha</th>
                    <td>{{ $inspeccion->fecha_inspeccion ?? 'No registrada' }}</td>
                </tr>

                <tr>
                    <th>Resultado</th>
                    <td>{{ $inspeccion->resultado ?? 'No definido' }}</td>
                </tr>

                <tr>
                    <th>Observaciones</th>
                    <td>{{ $inspeccion->observaciones ?? 'Sin observaciones' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

</div>

<div class="card" style="margin-bottom:20px;">

    <h2>Detalles de la Inspección</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID Detalle</th>
                    <th>Información Registrada</th>
                </tr>
            </thead>

            <tbody>
                @forelse($inspeccion->detalles as $detalle)
                    <tr>
                        <td>{{ $detalle->getKey() }}</td>
                        <td>
                            @foreach($detalle->getAttributes() as $campo => $valor)
                                @if($campo !== $detalle->getKeyName())
                                    <div style="margin-bottom:5px;">
                                        <strong>
                                            {{ ucfirst(str_replace('_', ' ', $campo)) }}:
                                        </strong>
                                        {{ $valor ?? 'N/A' }}
                                    </div>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" style="text-align:center; padding:20px;">
                            No hay detalles registrados para esta inspección.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<div class="card">

    <h2>Avalúos del Vehículo</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID Avalúo</th>
                    <th>Información Registrada</th>
                </tr>
            </thead>

            <tbody>
                @forelse($inspeccion->avaluos as $avaluo)
                    <tr>
                        <td>{{ $avaluo->getKey() }}</td>
                        <td>
                            @foreach($avaluo->getAttributes() as $campo => $valor)
                                @if($campo !== $avaluo->getKeyName())
                                    <div style="margin-bottom:5px;">
                                        <strong>
                                            {{ ucfirst(str_replace('_', ' ', $campo)) }}:
                                        </strong>
                                        {{ $valor ?? 'N/A' }}
                                    </div>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" style="text-align:center; padding:20px;">
                            No hay avalúos registrados para esta inspección.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<div style="margin-top:15px;">
    <a href="{{ route('inspecciones.index') }}" class="btn">
        Volver a Inspecciones
    </a>
</div>

@endsection
