@extends('layouts.app')
@section('title', 'Finanzas - COFIHO V2')
@section('page-title', 'Gestión Financiera')
@section('content')
    <style>
        .fin-wrap {
            max-width: 1400px;
            margin: 22px auto;
            padding: 0 18px;
            color: #142443
        }

        .fin-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px
        }

        .fin-head h2 {
            font-size: 21px;
            font-weight: 800;
            margin: 0 0 4px
        }

        .fin-sub {
            font-size: 12px;
            color: #7081a3;
            margin: 0
        }

        .fin-card {
            background: white;
            border: 1px solid #e1e8f4;
            border-radius: 13px;
            padding: 19px;
            margin-bottom: 16px
        }

        .fin-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 16px
        }

        .fin-grid.two {
            grid-template-columns: repeat(2, minmax(0, 1fr))
        }

        .fin-metric {
            background: white;
            border: 1px solid #e1e8f4;
            border-radius: 12px;
            padding: 17px
        }

        .fin-metric small,
        .fin-label {
            display: block;
            font-size: 11px;
            color: #667a9d;
            font-weight: 700;
            margin-bottom: 8px
        }

        .fin-metric strong {
            font-size: 22px;
            color: #0e2247
        }

        .fin-metric p {
            font-size: 11px;
            color: #8190ab;
            margin: 5px 0 0
        }

        .fin-scroll {
            overflow-x: auto
        }

        .fin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px
        }

        .fin-table th {
            background: #f0f4fa;
            color: #435b84;
            text-transform: uppercase;
            letter-spacing: .05em;
            text-align: left;
            font-size: 10px;
            padding: 13px 11px
        }

        .fin-table td {
            padding: 12px 11px;
            border-bottom: 1px solid #edf1f7
        }

        .fin-table tr:last-child td {
            border-bottom: 0
        }

        .fin-pill {
            display: inline-block;
            border-radius: 20px;
            padding: 5px 9px;
            font-weight: 800;
            font-size: 10px;
            background: #e6eeff;
            color: #2256c5
        }

        .fin-pill.ok {
            background: #ddf9e7;
            color: #087943
        }

        .fin-pill.warn {
            background: #fff0cc;
            color: #a65b00
        }

        .fin-pill.bad {
            background: #ffe4e4;
            color: #a92828
        }

        .fin-btn {
            display: inline-block;
            background: #245ce7;
            color: #fff !important;
            text-decoration: none;
            padding: 9px 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700
        }

        .fin-btn.secondary {
            background: #eef2f9;
            color: #1c3764 !important
        }

        .fin-actions {
            display: flex;
            gap: 9px;
            flex-wrap: wrap;
            margin-top: 14px
        }

        .fin-fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px
        }

        .fin-field {
            background: #f7f9fd;
            border: 1px solid #e4ebf6;
            border-radius: 8px;
            padding: 13px;
            overflow-wrap: anywhere
        }

        .fin-field strong {
            font-size: 13px
        }

        .fin-section-title {
            font-size: 15px;
            font-weight: 800;
            margin: 0 0 4px
        }

        .fin-filter {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: end;
            background: #f7f9fd;
            border: 1px solid #e5ebf6;
            border-radius: 10px;
            padding: 12px;
            margin: 16px 0
        }

        .fin-filter label {
            font-size: 11px;
            color: #54698e;
            font-weight: 700;
            flex: 1;
            min-width: 150px
        }

        .fin-filter input,
        .fin-filter select {
            display: block;
            width: 100%;
            margin-top: 5px;
            border: 1px solid #dce4f0;
            border-radius: 7px;
            padding: 9px;
            background: white;
            color: #1a3155
        }

        .fin-filter button {
            border: 0;
            border-radius: 7px;
            padding: 10px 13px;
            background: #eaf0fa;
            color: #294b7c;
            cursor: pointer
        }

        .fin-note {
            color: #8291ab;
            font-size: 11px
        }

        .fin-num {
            font-variant-numeric: tabular-nums;
            font-weight: 750;
            white-space: nowrap
        }

        @media(max-width:800px) {
            .fin-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .fin-fields {
                grid-template-columns: 1fr
            }
        }

        @media(max-width:500px) {

            .fin-grid,
            .fin-grid.two {
                grid-template-columns: 1fr
            }
        }
    </style>
    @php
        $aprobadas = $transacciones->filter(fn($t) => strtoupper((string) $t->estado) === 'APROBADA');
        $pagos = $aprobadas->filter(fn($t) => strtoupper((string) $t->tipo) === 'PAGO')->sum('monto');
        $enganches = $aprobadas->filter(fn($t) => strtoupper((string) $t->tipo) === 'ENGANCHE')->sum('monto');
        $devoluciones = $aprobadas->filter(fn($t) => strtoupper((string) $t->tipo) === 'DEVOLUCION')->sum('monto');
    @endphp
    <div class="fin-wrap">
        <div class="fin-head">
            <div>
                <h2>Resumen financiero</h2>
                <p class="fin-sub">Consulta las operaciones registradas y su estado de aprobación.</p>
            </div><span class="fin-pill">{{ $transacciones->count() }} transacciones</span>
        </div>
        <div class="fin-grid">
            <div class="fin-metric"><small>Pagos aprobados</small><strong>Q {{ number_format($pagos, 2) }}</strong>
                <p>Transacciones de tipo PAGO</p>
            </div>
            <div class="fin-metric"><small>Enganches aprobados</small><strong>Q {{ number_format($enganches, 2) }}</strong>
                <p>Transacciones de tipo ENGANCHE</p>
            </div>
            <div class="fin-metric"><small>Devoluciones aprobadas</small><strong>Q
                    {{ number_format($devoluciones, 2) }}</strong>
                <p>Montos devueltos</p>
            </div>
            <div class="fin-metric"><small>Flujo neto registrado</small><strong>Q
                    {{ number_format($pagos + $enganches - $devoluciones, 2) }}</strong>
                <p>Pagos + enganches − devoluciones; no equivale a conciliación</p>
            </div>
        </div>
        <div class="fin-card">
            <h3 class="fin-section-title">Historial de transacciones</h3>
            <p class="fin-sub">Filtra por identificador, tipo o estado de la operación.</p>
            <div class="fin-filter"><label>Buscar transacción<input id="finBuscar"
                        placeholder="ID de transacción, venta o reserva" oninput="finFiltrar()"></label><label>Tipo<select
                        id="finTipo" onchange="finFiltrar()">
                        <option value="">Todos los tipos</option>
                        <option>PAGO</option>
                        <option>ENGANCHE</option>
                        <option>DEVOLUCION</option>
                    </select></label><label>Estado<select id="finEstado" onchange="finFiltrar()">
                        <option value="">Todos los estados</option>
                        @foreach ($transacciones->pluck('estado')->unique()->filter() as $estado)
                            <option value="{{ strtoupper($estado) }}">{{ $estado }}</option>
                        @endforeach
                    </select></label><button type="button"
                    onclick="document.getElementById('finBuscar').value='';document.getElementById('finTipo').value='';document.getElementById('finEstado').value='';finFiltrar()">Limpiar
                    filtros</button></div>
            <p class="fin-note" id="finConteo">Mostrando {{ $transacciones->count() }} transacciones</p>
            <div class="fin-scroll">
                <table class="fin-table">
                    <thead>
                        <tr>
                            <th>Transacción</th>
                            <th>Venta</th>
                            <th>Reserva</th>
                            <th>Tipo</th>
                            <th>Monto</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transacciones as $t)
                            @php
                                $tipo = strtoupper((string) $t->tipo);
                                $estado = strtoupper((string) $t->estado);
                            @endphp
                            <tr class="fin-registro"
                                data-search="{{ $t->id_transaccion }} {{ $t->id_venta }} {{ $t->id_reserva }}"
                                data-tipo="{{ $tipo }}" data-estado="{{ $estado }}">
                                <td><strong>#{{ $t->id_transaccion }}</strong></td>
                                <td>{{ $t->id_venta ? '#' . $t->id_venta : '—' }}</td>
                                <td>{{ $t->id_reserva ? '#' . $t->id_reserva : '—' }}</td>
                                <td><span
                                        class="fin-pill {{ $tipo === 'ENGANCHE' ? 'ok' : ($tipo === 'DEVOLUCION' ? 'warn' : '') }}">{{ $tipo }}</span>
                                </td>
                                <td class="fin-num">{{ $tipo === 'DEVOLUCION' ? '− ' : '' }}Q
                                    {{ number_format(abs((float) $t->monto), 2) }}</td>
                                <td><span
                                        class="fin-pill {{ $estado === 'APROBADA' ? 'ok' : ($estado === 'RECHAZADA' ? 'bad' : 'warn') }}">{{ $estado }}</span>
                                </td>
                                <td>{{ $t->fecha_transaccion ? \Carbon\Carbon::parse($t->fecha_transaccion)->format('d/m/Y H:i') : '—' }}
                                </td>
                                <td><a class="fin-btn" href="{{ route('finanzas.show', $t->id_transaccion) }}">Ver
                                        detalle</a></td>
                            </tr>
                        @empty <tr>
                                <td colspan="8">No hay transacciones registradas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        function finFiltrar() {
            const q = document.getElementById('finBuscar').value.trim().toLowerCase(),
                t = document.getElementById('finTipo').value,
                e = document.getElementById('finEstado').value;
            let n = 0;
            document.querySelectorAll('.fin-registro').forEach(r => {
                let ok = r.dataset.search.toLowerCase().includes(q) && (!t || r.dataset.tipo === t) && (!e || r
                    .dataset.estado === e);
                r.style.display = ok ? '' : 'none';
                if (ok) n++
            });
            document.getElementById('finConteo').textContent = 'Mostrando ' + n + ' transacciones';
        }
    </script>
@endsection
