
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

<div class="card" style="margin-top:20px;">
    <h2>Registrar avalúo del vehículo</h2>

    @if(session('success'))
        <p style="color:green;">{{ session('success') }}</p>
    @endif

    @if($errors->any())
        <div style="color:#b91c1c; margin-bottom:15px;">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('inspecciones.avaluos.store') }}">
        @csrf
        <input type="hidden" name="id_inspeccion" value="{{ $inspeccion->id_inspeccion }}">

        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:15px;">
            <div>
                <label for="id_usuario_avaluo">ID del inspector</label>
                <input id="id_usuario_avaluo" type="number" name="id_usuario"
                       value="{{ old('id_usuario', 3) }}" min="1" required
                       style="width:100%; padding:10px;">
            </div>
            <div>
                <label for="valor_estimado">Valor estimado (Q)</label>
                <input id="valor_estimado" type="number" name="valor_estimado"
                       value="{{ old('valor_estimado') }}" min="0" step="0.01" required
                       style="width:100%; padding:10px;">
            </div>
            <div>
                <label for="estado_avaluo">Estado del avalúo</label>
                <select id="estado_avaluo" name="estado" required
                        style="width:100%; padding:10px;">
                    @foreach(['PENDIENTE', 'APROBADO', 'RECHAZADO'] as $estado)
                        <option value="{{ $estado }}"
                            @selected(old('estado', 'PENDIENTE') === $estado)>
                            {{ $estado }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="margin-top:15px;">
            <label for="observaciones_avaluo">Observaciones del avalúo</label>
            <textarea id="observaciones_avaluo" name="observaciones" rows="3"
                      style="width:100%; padding:10px;">{{ old('observaciones') }}</textarea>
        </div>

        @if($inspeccion->resultado !== 'APROBADA')
            <p style="margin-top:15px; color:#b45309;">
                La inspección no está aprobada. Solo se puede registrar
                un avalúo pendiente o rechazado.
            </p>
        @endif

        <button type="submit" class="btn" style="margin-top:15px;">
            Registrar avalúo
        </button>
    </form>
</div>

<div style="margin-top:15px;">
    <a href="{{ route('inspecciones.index') }}" class="btn">
        Volver a Inspecciones
    </a>
</div>

@endsection
