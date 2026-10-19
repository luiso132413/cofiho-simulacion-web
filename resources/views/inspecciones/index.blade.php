
@extends('layouts.app')

@section('title', 'Inspecciones - COFIHO V2')
@section('page-title', 'Gestión de Inspecciones')

@section('content')

@if(session('success'))
    <div class="card" style="margin-bottom:15px; color:green;">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="card" style="margin-bottom:15px; color:#b91c1c;">
        <strong>No se pudo registrar la inspección:</strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="card" style="margin-bottom:20px;">

    <h2>Registrar inspección técnica</h2>

    <form method="POST" action="{{ route('inspecciones.store') }}">
        @csrf

        <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(220px,1fr)); gap:15px;">

            <div>
                <label for="id_vehiculo">ID del vehículo usado</label>
                <input type="number" id="id_vehiculo" name="id_vehiculo"
                       min="1" required
                       value="{{ old('id_vehiculo') }}"
                       style="width:100%; padding:10px;">
            </div>

            <div>
                <label for="id_usuario">ID del inspector</label>
                <input type="number" id="id_usuario" name="id_usuario"
                       min="1" required
                       value="{{ old('id_usuario', 3) }}"
                       style="width:100%; padding:10px;">
            </div>

            <div>
                <label for="resultado">Resultado</label>
                <select id="resultado" name="resultado" required
                        style="width:100%; padding:10px;">
                    @foreach(['PENDIENTE', 'APROBADA', 'RECHAZADA'] as $estado)
                        <option value="{{ $estado }}"
                            @selected(old('resultado', 'PENDIENTE') === $estado)>
                            {{ $estado }}
                        </option>
                    @endforeach
                </select>
            </div>

        </div>

        <div style="margin-top:15px;">
            <label for="observaciones">Observaciones generales</label>
            <textarea id="observaciones" name="observaciones"
                      rows="3"
                      style="width:100%; padding:10px;">{{ old('observaciones') }}</textarea>
        </div>

        <h3 style="margin-top:20px;">Detalles técnicos</h3>

        <p>Registra el estado de cada componente inspeccionado.</p>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Componente</th>
                        <th>Estado</th>
                        <th>Observación</th>
                        <th>Costo estimado (Q)</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach(['Motor', 'Frenos', 'Carrocería'] as $i => $componente)
                        <tr>
                            <td>
                                <input type="text"
                                       name="detalles[{{ $i }}][componente]"
                                       value="{{ old("detalles.$i.componente", $componente) }}"
                                       required style="width:100%; padding:8px;">
                            </td>

                            <td>
                                <select name="detalles[{{ $i }}][estado]"
                                        required style="width:100%; padding:8px;">
                                    @foreach(['BUENO', 'REGULAR', 'MALO', 'NO_APLICA'] as $estado)
                                        <option value="{{ $estado }}"
                                            @selected(old("detalles.$i.estado", 'BUENO') === $estado)>
                                            {{ $estado }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>

                            <td>
                                <input type="text"
                                       name="detalles[{{ $i }}][observacion]"
                                       value="{{ old("detalles.$i.observacion") }}"
                                       style="width:100%; padding:8px;">
                            </td>

                            <td>
                                <input type="number"
                                       name="detalles[{{ $i }}][costo_estimado]"
                                       value="{{ old("detalles.$i.costo_estimado", 0) }}"
                                       min="0" step="0.01" required
                                       style="width:100%; padding:8px;">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <button type="submit" class="btn" style="margin-top:15px;">
            Registrar inspección
        </button>
    </form>
</div>

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
                        <td>{{ $inspeccion->id_inspeccion }}</td>
                        <td>{{ $inspeccion->id_vehiculo }}</td>
                        <td>{{ $inspeccion->vehiculo->vin ?? 'No disponible' }}</td>
                        <td>{{ $inspeccion->id_usuario ?? 'No asignado' }}</td>
                        <td>{{ $inspeccion->fecha_inspeccion ?? 'No registrada' }}</td>
                        <td>{{ $inspeccion->resultado ?? 'PENDIENTE' }}</td>
                        <td>{{ $inspeccion->observaciones ?? 'Sin observaciones' }}</td>
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
