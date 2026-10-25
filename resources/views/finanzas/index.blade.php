
@extends('layouts.app')

@section('title', 'Finanzas - COFIHO V2')

@section('page-title', 'Gestión Financiera')

@section('content')

<div class="card">

    <h2>Transacciones Financieras</h2>

    {{-- FILTROS --}}
    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:15px; margin-bottom:20px;">

        <div>
            <label for="buscarFinanza">Buscar transacción</label>
            <input
                type="text"
                id="buscarFinanza"
                placeholder="ID transacción, venta o reserva"
                oninput="filtrarFinanzas()"
                style="width:100%; padding:10px; margin-top:5px;"
            >
        </div>

        <div>
            <label for="filtroTipo">Tipo de transacción</label>
            <select
                id="filtroTipo"
                onchange="filtrarFinanzas()"
                style="width:100%; padding:10px; margin-top:5px;"
            >
                <option value="">Todos los tipos</option>
                @foreach($transacciones->pluck('tipo')->filter()->unique()->sort() as $tipo)
                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="filtroEstado">Estado</label>
            <select
                id="filtroEstado"
                onchange="filtrarFinanzas()"
                style="width:100%; padding:10px; margin-top:5px;"
            >
                <option value="">Todos los estados</option>
                @foreach($transacciones->pluck('estado')->filter()->unique()->sort() as $estado)
                    <option value="{{ $estado }}">{{ $estado }}</option>
                @endforeach
            </select>
        </div>

        <div style="display:flex; align-items:end;">
            <button
                type="button"
                class="btn"
                onclick="limpiarFiltrosFinanzas()"
                style="width:100%;"
            >
                Limpiar filtros
            </button>
        </div>

    </div>

    <div style="margin-bottom:12px;">
        <span id="contadorFinanzas">
            Mostrando {{ $transacciones->count() }} transacciones
        </span>
    </div>

    {{-- LISTADO --}}
    <div class="table-responsive">
        <table id="tablaFinanzas">
            <thead>
                <tr>
                    <th>ID Transacción</th>
                    <th>ID Venta</th>
                    <th>ID Reserva</th>
                    <th>Método de Pago</th>
                    <th>Tipo</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Detalle</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transacciones as $transaccion)
                    <tr class="fila-finanza"
                        data-transaccion="{{ $transaccion->id_transaccion }}"
                        data-venta="{{ $transaccion->id_venta ?? '' }}"
                        data-reserva="{{ $transaccion->id_reserva ?? '' }}"
                        data-tipo="{{ $transaccion->tipo }}"
                        data-estado="{{ $transaccion->estado }}"
                    >
                        <td>{{ $transaccion->id_transaccion }}</td>

                        <td>{{ $transaccion->id_venta ?? 'N/A' }}</td>

                        <td>{{ $transaccion->id_reserva ?? 'N/A' }}</td>

                        <td>{{ $transaccion->id_metodo_pago }}</td>

                        <td>{{ $transaccion->tipo }}</td>

                        <td>
                            Q {{ number_format((float) $transaccion->monto, 2) }}
                        </td>

                        <td>{{ $transaccion->estado }}</td>

                        <td>
                            {{ $transaccion->fecha_transaccion ?? 'No registrada' }}
                        </td>

                        <td>
                            <a href="{{ route('finanzas.show', $transaccion->id_transaccion) }}"
                               class="btn">
                                Ver
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" style="text-align:center;">
                            No hay transacciones financieras registradas.
                        </td>
                    </tr>
                @endforelse

                @if($transacciones->isNotEmpty())
                    <tr id="sinResultadosFinanzas" style="display:none;">
                        <td colspan="9" style="text-align:center; padding:20px;">
                            No se encontraron transacciones con los filtros seleccionados.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

</div>

<script>
function filtrarFinanzas() {
    const buscar = document.getElementById('buscarFinanza')
        .value.trim().toLowerCase();

    const tipo = document.getElementById('filtroTipo').value;
    const estado = document.getElementById('filtroEstado').value;

    const filas = document.querySelectorAll(
        '#tablaFinanzas .fila-finanza'
    );

    let visibles = 0;

    filas.forEach(function(fila) {
        const idTransaccion = fila.dataset.transaccion || '';
        const idVenta = fila.dataset.venta || '';
        const idReserva = fila.dataset.reserva || '';

        const tipoFila = fila.dataset.tipo || '';
        const estadoFila = fila.dataset.estado || '';

        const coincideBusqueda =
            buscar === '' ||
            idTransaccion.includes(buscar) ||
            idVenta.includes(buscar) ||
            idReserva.includes(buscar);

        const coincideTipo =
            tipo === '' || tipoFila === tipo;

        const coincideEstado =
            estado === '' || estadoFila === estado;

        const mostrar =
            coincideBusqueda &&
            coincideTipo &&
            coincideEstado;

        fila.style.display = mostrar ? '' : 'none';

        if (mostrar) {
            visibles++;
        }
    });

    document.getElementById('contadorFinanzas')
        .textContent = 'Mostrando ' + visibles + ' transacciones';

    const sinResultados = document.getElementById(
        'sinResultadosFinanzas'
    );

    if (sinResultados) {
        sinResultados.style.display =
            visibles === 0 ? '' : 'none';
    }
}

function limpiarFiltrosFinanzas() {
    document.getElementById('buscarFinanza').value = '';
    document.getElementById('filtroTipo').value = '';
    document.getElementById('filtroEstado').value = '';

    filtrarFinanzas();
}
</script>

@endsection
