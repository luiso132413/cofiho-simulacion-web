@extends('layouts.app')
@section('title', 'Reporte de Ventas - COFIHO V2')
@section('page-title', 'Reporte de Ventas')
@section('content')
    <style>
        .report-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
            flex-wrap: wrap
        }

        .report-kicker {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.4px;
            color: #2563eb;
            text-transform: uppercase
        }

        .report-sub {
            font-size: 13px;
            color: #64748b;
            line-height: 1.6;
            margin: 7px 0 0
        }

        .report-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
            margin-bottom: 20px
        }

        .report-tile {
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 22px;
            background: #fff;
            transition: transform .2s, box-shadow .2s
        }

        .report-tile:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px #152d5210
        }

        .report-icon {
            display: inline-grid;
            place-items: center;
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #eff6ff;
            color: #2563eb;
            font-size: 21px;
            margin-bottom: 16px
        }

        .report-tile h3 {
            font-size: 16px;
            margin: 0 0 9px;
            color: #0f172a
        }

        .report-tile p {
            font-size: 13px;
            line-height: 1.6;
            color: #64748b;
            min-height: 44px;
            margin: 0 0 18px
        }

        .report-stat-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(175px, 1fr));
            gap: 12px;
            margin-bottom: 20px
        }

        .report-stat {
            padding: 18px 20px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 13px
        }

        .report-stat small {
            display: block;
            font-size: 12px;
            color: #64748b;
            margin-bottom: 8px
        }

        .report-stat strong {
            font-size: 25px;
            color: #0f172a;
            font-weight: 800
        }

        .report-stat em {
            display: block;
            font-style: normal;
            color: #64748b;
            font-size: 11px;
            margin-top: 5px
        }

        .report-card {
            margin-bottom: 20px
        }

        .report-card h2 {
            margin-bottom: 8px
        }

        .report-chart {
            height: 255px;
            position: relative
        }

        .report-filter {
            display: flex;
            gap: 12px;
            align-items: end;
            flex-wrap: wrap;
            margin: 16px 0
        }

        .report-filter label {
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            display: block;
            margin-bottom: 6px
        }

        .report-filter input,
        .report-filter select {
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            background: white;
            color: #0f172a;
            min-width: 180px;
            max-width: 100%
        }

        .report-filter button {
            padding: 10px 15px;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            background: #fff;
            cursor: pointer
        }

        .report-table {
            width: 100%
        }

        .report-table th {
            white-space: nowrap
        }

        .report-table td {
            vertical-align: middle
        }

        .report-pill {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 800
        }

        .report-pill.ok {
            background: #dcfce7;
            color: #166534
        }

        .report-pill.warn {
            background: #fef3c7;
            color: #92400e
        }

        .report-pill.bad {
            background: #fee2e2;
            color: #991b1b
        }

        .report-pill.neutral {
            background: #e2e8f0;
            color: #334155
        }

        .report-empty {
            padding: 30px;
            text-align: center;
            color: #64748b
        }

        .report-footer {
            margin-top: 16px
        }

        .report-muted {
            font-size: 12px;
            color: #64748b
        }

        @media(max-width:700px) {
            .report-chart {
                height: 220px
            }

            .report-stat strong {
                font-size: 21px
            }
        }
    </style>
    <style>
        .sales-form {
            display: flex;
            gap: 12px;
            align-items: end;
            flex-wrap: wrap;
            margin: 15px 0
        }

        .sales-form label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #475569;
            margin-bottom: 6px
        }

        .sales-form input,
        .sales-form select {
            padding: 10px 12px;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            background: #fff
        }

        .sales-form button {
            padding: 11px 17px;
            background: #2563eb;
            border: 0;
            color: white;
            border-radius: 9px;
            font-weight: 700;
            cursor: pointer
        }

        .sales-table {
            width: 100%
        }

        .sales-table th,
        .sales-table td {
            padding: 11px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0
        }

        .sales-table th {
            font-size: 11px;
            color: #475569;
            text-transform: uppercase
        }

        .sales-table td {
            font-size: 13px
        }

        .sales-scroll {
            overflow-x: auto
        }
    </style>
    <div class="report-head">
        <div>
            <div class="report-kicker">Análisis / Ventas</div>
            <h2 style="margin:6px 0">Reporte comercial de ventas</h2>
            <p class="report-sub">Resultados de ventas registradas en Oracle, filtrados por fecha y estado.</p>
        </div><a class="btn" href="{{ route('reportes.index') }}">← Centro de reportes</a>
    </div>
    <div class="card report-card">
        <h2>Filtros del reporte</h2>
        <form method="GET" action="{{ route('reportes.ventas') }}" class="sales-form">
            <div><label for="desde">Desde</label><input type="date" name="desde" id="desde"
                    value="{{ $desde }}"></div>
            <div><label for="hasta">Hasta</label><input type="date" name="hasta" id="hasta"
                    value="{{ $hasta }}"></div>
            <div><label for="estado">Estado</label><select name="estado" id="estado">
                    <option value="TODOS" @selected($estado === 'TODOS')>Todos</option>
                    <option value="CONFIRMADA" @selected($estado === 'CONFIRMADA')>Confirmada</option>
                </select></div><button type="submit">Aplicar filtros</button><a class="btn"
                href="{{ route('reportes.ventas') }}">Limpiar</a>
        </form>
    </div>
    <div class="report-stat-grid">
        <div class="report-stat"><small>Ventas del período</small><strong>{{ $ventas->count() }}</strong><em>Operaciones
                según filtros</em></div>
        <div class="report-stat"><small>Monto total vendido</small><strong style="font-size:20px">Q
                {{ number_format($ventas->sum('total'), 2) }}</strong><em>Importe de ventas seleccionadas</em></div>
        <div class="report-stat"><small>Valor promedio por venta</small><strong style="font-size:20px">Q
                {{ number_format($ventas->avg('total') ?? 0, 2) }}</strong><em>Promedio por operación</em></div>
        <div class="report-stat"><small>Vehículos vendidos</small><strong>{{ $totalVehiculos }}</strong><em>Unidades en ventas filtradas</em></div>
        <div class="report-stat"><small>Vendedores con
                ventas</small><strong>{{ $porVendedor->count() }}</strong><em>Usuarios con operaciones</em></div>
    </div>
    <div class="card report-card">
        <h2>Ventas por mes</h2>
        <p class="report-sub">Monto vendido agrupado por mes.</p>
        <div class="report-chart"><canvas id="graficaVentasMes"></canvas></div>
    </div>
    <div class="card report-card">
        <h2>Ventas por vendedor</h2>
        <p class="report-sub">Ranking por monto vendido, según el usuario que registró cada venta.</p>
        <div class="sales-scroll">
            <table class="sales-table">
                <thead>
                    <tr>
                        <th>Vendedor</th>
                        <th>Ventas</th>
                        <th>Vehículos</th>
                        <th>Monto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($porVendedor as $id => $grupo)
                        <tr>
                            <td>{{ $grupo['nombre'] }}</td>
                            <td>{{ $grupo['ventas'] }}</td>
                            <td>{{ $grupo['vehiculos'] }}</td>
                            <td>Q {{ number_format($grupo['monto'], 2) }}</td>
                    </tr>@empty<tr>
                            <td colspan="4">No hay datos en el período.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card report-card">
        <h2>Detalle de ventas</h2>
        <p class="report-sub">Operaciones registradas que cumplen los filtros.</p>
        <div class="sales-scroll">
            <table class="sales-table">
                <thead>
                    <tr>
                        <th>Venta</th>
                        <th>Fecha</th>
                        <th>Cliente</th>
                        <th>Vendedor</th>
                        <th>Vehículo(s)</th>
                        <th>Estado</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($ventas as $v)
                        <tr>
                            <td>#{{ $v->id_venta }}</td>
                            <td>{{ $v->fecha_venta }}</td>
                            <td>{{ trim(($v->cliente_nombre ?? '') . ' ' . ($v->cliente_apellido ?? '')) ?: 'Cliente #' . $v->id_cliente }}</td>
                            <td>{{ trim(($v->persona_nombres ?? '') . ' ' . ($v->persona_apellidos ?? '')) ?: ($v->usuario_nombre ?: 'Usuario #' . $v->id_usuario) }}</td>
                            <td>
                                @forelse($detallesPorVenta->get($v->id_venta, collect()) as $detalle)
                                    <div><strong>{{ trim(($detalle->marca_nombre ?? '') . ' ' . ($detalle->modelo_nombre ?? '')) ?: 'Vehículo #' . $detalle->id_vehiculo }}</strong></div>
                                    <div class="report-muted">VIN: {{ $detalle->vin ?? 'Sin VIN' }} · Año: {{ $detalle->anio ?? 'N/D' }}</div>
                                @empty
                                    <span class="report-muted">Sin detalle de vehículo</span>
                                @endforelse
                            </td>
                            <td><span
                                    class="report-pill {{ $v->estado === 'CONFIRMADA' ? 'ok' : 'neutral' }}">{{ $v->estado }}</span>
                            </td>
                            <td>Q {{ number_format((float) $v->total, 2) }}</td>
                    </tr>@empty<tr>
                            <td colspan="7">No se encontraron ventas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const el = document.getElementById('graficaVentasMes');
            if (!el || !window.Chart) return;
            new Chart(el, {
                type: 'bar',
                data: {
                    labels: @json($meses->keys()->values()),
                    datasets: [{
                        label: 'Ventas (Q)',
                        data: @json($meses->values()),
                        backgroundColor: '#2563eb',
                        borderRadius: 7,
                        maxBarThickness: 65
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            })
        });
    </script>
@endsection
