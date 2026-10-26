@extends('layouts.app')

@section('title', 'Conciliación Financiera - COFIHO V2')

@section('page-title', 'Reporte de Conciliación Financiera')

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
    @php
        $datosGrafica = $transacciones
            ->map(
                fn($t) => [
                    'venta' => 'Venta #' . $t->id_venta,
                    'total_venta' => (float) $t->total_venta,
                    'total_pagado' => (float) $t->total_pagado,
                    'diferencia' => (float) $t->diferencia,
                ],
            )
            ->values();
        $conciliadas = $transacciones
            ->filter(fn($t) => strtoupper((string) $t->estado_conciliacion) === 'CONCILIADA')
            ->count();
    @endphp
    <div class="report-head">
        <div>
            <div class="report-kicker">Finanzas / Control</div>
            <h2 style="margin:6px 0">Conciliación financiera</h2>
            <p class="report-sub">Compara ventas y pagos registrados en Oracle.</p>
        </div><a href="{{ route('reportes.index') }}" class="btn">← Centro de reportes</a>
    </div>
    <div class="report-stat-grid">
        <div class="report-stat"><small>Ventas analizadas</small><strong>{{ $transacciones->count() }}</strong><em>Registros
                de conciliación</em></div>
        <div class="report-stat"><small>Conciliadas</small><strong>{{ $conciliadas }}</strong><em>Estado conciliado</em>
        </div>
        <div class="report-stat"><small>Total ventas</small><strong style="font-size:20px">Q
                {{ number_format((float) $transacciones->sum('total_venta'), 2) }}</strong><em>Importe acumulado</em></div>
        <div class="report-stat"><small>Diferencia acumulada</small><strong style="font-size:20px">Q
                {{ number_format((float) $transacciones->sum('diferencia'), 2) }}</strong><em>Según la vista de
                conciliación</em></div>
    </div>
    <div class="card report-card">
        <h2>Comparación de ventas y pagos</h2>
        <p class="report-sub">Montos expresados en quetzales (Q).</p>
        @if ($transacciones->isNotEmpty())
            <div style="overflow-x:auto">
                <div class="report-chart" style="min-width:{{ max(580, $transacciones->count() * 105) }}px"><canvas
                        id="graficaConciliacion"></canvas></div>
        </div>@else<div class="report-empty">No hay registros disponibles para graficar.</div>
        @endif
    </div>
    <div class="card report-card">
        <h2>Detalle de conciliación</h2>
        <p class="report-sub">Revisa los estados y las diferencias de cada venta.</p>
        <div class="report-filter">
            <div><label for="concBuscar">Buscar venta</label><input id="concBuscar" placeholder="Número de venta"></div>
            <div><label for="concEstado">Conciliación</label><select id="concEstado">
                    <option value="">Todos los estados</option>
                    @foreach ($transacciones->pluck('estado_conciliacion')->unique() as $estado)
                        <option value="{{ $estado }}">{{ $estado }}</option>
                    @endforeach
                </select>
            </div><button id="concLimpiar" type="button">Limpiar filtros</button><span id="concContador"
                class="report-muted"></span>
        </div>
        <div class="table-responsive">
            <table class="report-table" id="concTabla">
                <thead>
                    <tr>
                        <th>ID venta</th>
                        <th>Fecha</th>
                        <th>Estado venta</th>
                        <th>Total venta</th>
                        <th>Total pagado</th>
                        <th>Diferencia</th>
                        <th>Conciliación</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transacciones as $t)
                        <tr data-search="{{ $t->id_venta }}" data-estado="{{ $t->estado_conciliacion }}">
                            <td><strong>#{{ $t->id_venta }}</strong></td>
                            <td>{{ $t->fecha_venta ?? 'No registrada' }}</td>
                            <td>{{ $t->estado_venta }}</td>
                            <td>Q {{ number_format((float) $t->total_venta, 2) }}</td>
                            <td>Q {{ number_format((float) $t->total_pagado, 2) }}</td>
                            <td>Q {{ number_format((float) $t->diferencia, 2) }}</td>
                            <td><span
                                    class="report-pill {{ strtoupper((string) $t->estado_conciliacion) === 'CONCILIADA' ? 'ok' : 'warn' }}">{{ $t->estado_conciliacion }}</span>
                            </td>
                    </tr>@empty<tr>
                            <td colspan="7" class="report-empty">No hay conciliaciones registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const c = document.getElementById('graficaConciliacion');
            if (c && window.Chart) {
                const d = @json($datosGrafica);
                new Chart(c, {
                    type: 'bar',
                    data: {
                        labels: d.map(x => x.venta),
                        datasets: [{
                            label: 'Total venta',
                            data: d.map(x => x.total_venta),
                            backgroundColor: '#2563eb',
                            borderRadius: 4
                        }, {
                            label: 'Total pagado',
                            data: d.map(x => x.total_pagado),
                            backgroundColor: '#16a34a',
                            borderRadius: 4
                        }, {
                            label: 'Diferencia',
                            data: d.map(x => x.diferencia),
                            backgroundColor: '#f59e0b',
                            borderRadius: 4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top'
                            },
                            tooltip: {
                                callbacks: {
                                    label: x => x.dataset.label + ': Q ' + Number(x.parsed.y)
                                        .toLocaleString('es-GT', {
                                            minimumFractionDigits: 2
                                        })
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: v => 'Q ' + Number(v).toLocaleString('es-GT')
                                }
                            }
                        }
                    }
                })
            }
            const input = document.getElementById('concBuscar'),
                select = document.getElementById('concEstado'),
                rows = [...document.querySelectorAll('#concTabla tbody tr[data-search]')],
                count = document.getElementById('concContador');

            function filter() {
                let n = 0;
                rows.forEach(r => {
                    const show = r.dataset.search.includes(input.value.trim()) && (!select.value || r
                        .dataset.estado === select.value);
                    r.hidden = !show;
                    if (show) n++
                });
                count.textContent = `Mostrando ${n} de ${rows.length}`;
            }
            input.addEventListener('input', filter);
            select.addEventListener('change', filter);
            document.getElementById('concLimpiar').addEventListener('click', () => {
                input.value = '';
                select.value = '';
                filter()
            });
            filter()
        });
    </script>
@endsection
