
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
    <select
        name="detalles[{{ $i }}][componente]"
        required
        style="width:100%; padding:8px;"
    >
        @foreach(['Motor', 'Frenos', 'Carrocería'] as $opcion)
            <option
                value="{{ $opcion }}"
                @selected(old("detalles.$i.componente", $componente) === $opcion)
            >
                {{ $opcion }}
            </option>
        @endforeach
    </select>
</td>

                            
<td>
    <select
        name="detalles[{{ $i }}][estado]"
        class="estado-componente"
        required
        style="width:100%; padding:8px;"
    >
        @foreach(['BUENO', 'REGULAR', 'MALO', 'NO_APLICA'] as $estado)
            <option
                value="{{ $estado }}"
                @selected(old("detalles.$i.estado", 'BUENO') === $estado)
            >
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
    <input
        type="number"
        name="detalles[{{ $i }}][costo_estimado]"
        class="costo-componente"
        value="{{ old("detalles.$i.costo_estimado", 0) }}"
        min="0"
        step="0.01"
        required
        style="width:100%; padding:8px;"
    >
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

    
<div class="card">

    <h2>Listado de Inspecciones</h2>

    {{-- FILTROS --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:15px; margin-bottom:20px;">

        <div>
            <label for="buscarInspeccion">Buscar inspección</label>
            <input
                type="text"
                id="buscarInspeccion"
                placeholder="ID inspección, vehículo o VIN"
                oninput="filtrarInspecciones()"
                style="width:100%; padding:10px; margin-top:5px;"
            >
        </div>

        <div>
            <label for="filtroResultadoInspeccion">Resultado</label>
            <select
                id="filtroResultadoInspeccion"
                onchange="filtrarInspecciones()"
                style="width:100%; padding:10px; margin-top:5px;"
            >
                <option value="">Todos los resultados</option>

                @foreach(['PENDIENTE', 'APROBADA', 'RECHAZADA'] as $resultado)
                    <option value="{{ $resultado }}">
                        {{ $resultado }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display:flex; align-items:end;">
            <button
                type="button"
                class="btn"
                onclick="limpiarFiltrosInspecciones()"
                style="width:100%;"
            >
                Limpiar filtros
            </button>
        </div>

    </div>

    {{-- CONTADOR --}}
    <div style="margin-bottom:12px;">
        <span id="contadorInspecciones">
            Mostrando {{ $inspecciones->count() }} inspecciones
        </span>
    </div>

    {{-- TABLA --}}
    <div class="table-responsive">
        <table id="tablaInspecciones">

            <thead>
                <tr>
                    <th>ID Inspección</th>
                    <th>ID Vehículo</th>
                    <th>VIN</th>
                    <th>ID Usuario</th>
                    <th>Fecha</th>
                    <th>Resultado</th>
                    <th>Observaciones</th>
                    <th>Detalle</th>
                </tr>
            </thead>

            <tbody>
                @forelse($inspecciones as $inspeccion)

                    <tr
                        class="fila-inspeccion"
                        data-inspeccion="{{ $inspeccion->id_inspeccion }}"
                        data-vehiculo="{{ $inspeccion->id_vehiculo }}"
                        data-vin="{{ $inspeccion->vehiculo->vin ?? '' }}"
                        data-resultado="{{ $inspeccion->resultado ?? 'PENDIENTE' }}"
                    >

                        <td>{{ $inspeccion->id_inspeccion }}</td>

                        <td>{{ $inspeccion->id_vehiculo }}</td>

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
                            <a
                                href="{{ route('inspecciones.show', $inspeccion->id_inspeccion) }}"
                                class="btn"
                            >
                                Ver
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

                @if($inspecciones->isNotEmpty())
                    <tr id="sinResultadosInspecciones" style="display:none;">
                        <td colspan="8" style="text-align:center; padding:20px;">
                            No se encontraron inspecciones con los filtros seleccionados.
                        </td>
                    </tr>
                @endif

            </tbody>

        </table>
    </div>

</div>

</div>


<script>
function filtrarInspecciones() {

    const buscar = document.getElementById('buscarInspeccion')
        .value.trim().toLowerCase();

    const resultado = document.getElementById(
        'filtroResultadoInspeccion'
    ).value;

    const filas = document.querySelectorAll(
        '#tablaInspecciones .fila-inspeccion'
    );

    let visibles = 0;

    filas.forEach(function(fila) {

        const idInspeccion = fila.dataset.inspeccion || '';
        const idVehiculo = fila.dataset.vehiculo || '';
        const vin = (fila.dataset.vin || '').toLowerCase();
        const resultadoFila = fila.dataset.resultado || '';

        const coincideBusqueda =
            buscar === '' ||
            idInspeccion.includes(buscar) ||
            idVehiculo.includes(buscar) ||
            vin.includes(buscar);

        const coincideResultado =
            resultado === '' ||
            resultadoFila === resultado;

        const mostrar =
            coincideBusqueda &&
            coincideResultado;

        fila.style.display = mostrar ? '' : 'none';

        if (mostrar) {
            visibles++;
        }
    });

    document.getElementById('contadorInspecciones')
        .textContent = 'Mostrando ' + visibles + ' inspecciones';

    const sinResultados = document.getElementById(
        'sinResultadosInspecciones'
    );

    if (sinResultados) {
        sinResultados.style.display =
            visibles === 0 ? '' : 'none';
    }
}

function limpiarFiltrosInspecciones() {

    document.getElementById('buscarInspeccion').value = '';

    document.getElementById(
        'filtroResultadoInspeccion'
    ).value = '';

    filtrarInspecciones();
}
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {

    const filas = document.querySelectorAll(
        '.estado-componente'
    );

    filas.forEach(function (selector) {

        const fila = selector.closest('tr');

        if (!fila) return;

        const costo = fila.querySelector('.costo-componente');

        if (!costo) return;

        function actualizarCosto() {

            const estado = selector.value;

            if (estado === 'BUENO' || estado === 'NO_APLICA') {

                costo.value = '0.00';
                costo.readOnly = true;

                costo.style.backgroundColor = '#f1f5f9';
                costo.style.color = '#64748b';

            } else {

                costo.readOnly = false;

                costo.style.backgroundColor = '';
                costo.style.color = '';

            }
        }

        selector.addEventListener('change', function () {

            const estado = selector.value;

            if (estado === 'REGULAR' || estado === 'MALO') {
                costo.value = '';
            }

            actualizarCosto();
        });

        actualizarCosto();

    });

});
</script>

@endsection
