@extends('layouts.app')

@section('title', 'Antigüedad del Inventario - COFIHO V2')

@section('page-title', 'Reporte de Antigüedad del Inventario')

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
        $normal = $inventario->filter(fn($v) => (int) $v->dias_inventario <= 60)->count();
        $alerta60 = $inventario
            ->filter(fn($v) => (int) $v->dias_inventario > 60 && (int) $v->dias_inventario <= 90)
            ->count();
        $alerta90 = $inventario->filter(fn($v) => (int) $v->dias_inventario > 90)->count();
    @endphp
    <div class="report-head">
        <div>
            <div class="report-kicker">Inventario / Antigüedad</div>
            <h2 style="margin:6px 0">Antigüedad del inventario</h2>
            <p class="report-sub">Identifica vehículos con permanencia prolongada en los predios.</p>
        </div><a href="{{ route('reportes.index') }}" class="btn">← Centro de reportes</a>
    </div>
    <div class="report-stat-grid">
        <div class="report-stat"><small>Total vehículos</small><strong>{{ $inventario->count() }}</strong><em>Unidades
                analizadas</em></div>
        <div class="report-stat"><small>Normal · 0 a 60 días</small><strong
                style="color:#166534">{{ $normal }}</strong><em>Sin alerta</em></div>
        <div class="report-stat"><small>Alerta · 61 a 90 días</small><strong
                style="color:#92400e">{{ $alerta60 }}</strong><em>Evaluar promoción</em></div>
        <div class="report-stat"><small>Crítica · más de 90 días</small><strong
                style="color:#991b1b">{{ $alerta90 }}</strong><em>Evaluar reubicación o promoción</em></div>
    </div>
    <div class="card report-card">
        <h2>Distribución por antigüedad</h2>
        <p class="report-sub">Cantidad de vehículos por nivel de atención.</p>
        <div class="report-chart"><canvas id="graficaAntiguedad"></canvas></div>
    </div>
    <div class="card report-card">
        <h2>Resumen por sucursal / predio</h2>
        <p class="report-sub">Distribución de antigüedad y valor de inventario en cada ubicación.</p>
        <div class="table-responsive">
            <table class="report-table">
                <thead>
                    <tr>
                        <th>Predio</th>
                        <th>0–30 días</th>
                        <th>31–60 días</th>
                        <th>61–90 días</th>
                        <th>Más de 90 días</th>
                        <th>Total</th>
                        <th>Valor inventario</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resumenPredios as $p)
                        <tr>
                            <td><strong>{{ $p['predio'] }}</strong></td>
                            <td>{{ $p['dias_0_30'] }}</td>
                            <td>{{ $p['dias_31_60'] }}</td>
                            <td>{{ $p['dias_61_90'] }}</td>
                            <td>{{ $p['dias_mas_90'] }}</td>
                            <td><strong>{{ $p['total'] }}</strong></td>
                            <td>Q {{ number_format((float) $p['valor'], 2) }}</td>
                    </tr>@empty<tr>
                            <td colspan="7" class="report-empty">No hay inventario disponible.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card report-card">
        <h2>Detalle de vehículos</h2>
        <p class="report-sub">Vehículos ordenados de mayor a menor antigüedad.</p>
        <div class="report-filter">
            <div><label for="antBuscar">Buscar vehículo</label><input id="antBuscar" placeholder="VIN o ID"></div>
            <div><label for="antNivel">Nivel de alerta</label><select id="antNivel">
                    <option value="">Todos</option>
                    <option value="normal">Normal</option>
                    <option value="preventiva">Preventiva</option>
                    <option value="critica">Crítica</option>
                </select></div><button type="button" id="antLimpiar">Limpiar filtros</button><span id="antContador"
                class="report-muted"></span>
        </div>
        <div class="table-responsive">
            <table class="report-table" id="antTabla">
                <thead>
                    <tr>
                        <th>Predio</th>
                        <th>ID vehículo</th>
                        <th>VIN</th>
                        <th>Fecha ingreso</th>
                        <th>Días</th>
                        <th>Precio lista</th>
                        <th>Antigüedad</th>
                        <th>Alerta / Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($inventario->sortByDesc('dias_inventario') as $v)
                        @php
                            $dias = (int) $v->dias_inventario;
                            $nivel = $dias > 90 ? 'critica' : ($dias > 60 ? 'preventiva' : 'normal');
                            $accion = $dias > 90 ? 'Evaluar reubicación o promoción' : ($dias > 60 ? 'Evaluar promoción' : 'Sin acción requerida');
                        @endphp
                        <tr data-search="{{ strtolower($v->vin . ' ' . $v->id_vehiculo) }}" data-nivel="{{ $nivel }}">
                            <td>{{ $v->id_predio }}</td>
                            <td>{{ $v->id_vehiculo }}</td>
                            <td>{{ $v->vin }}</td>
                            <td>{{ $v->fecha_ingreso ?? 'No registrada' }}</td>
                            <td><strong>{{ $dias }}</strong></td>
                            <td>Q {{ number_format((float) $v->precio_lista, 2) }}</td>
                            <td>{{ $v->rango_antiguedad }}</td>
                            <td><span
                                    class="report-pill {{ $nivel === 'critica' ? 'bad' : ($nivel === 'preventiva' ? 'warn' : 'ok') }}">{{ strtoupper($nivel) }}</span>
                                <div class="report-muted" style="margin-top:5px">{{ $accion }}</div>
                            </td>
                    </tr>@empty<tr>
                            <td colspan="8" class="report-empty">No hay vehículos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const c = document.getElementById('graficaAntiguedad');
            if (c && window.Chart) new Chart(c, {
                type: 'bar',
                data: {
                    labels: ['Normal (0–60 días)', 'Alerta (61–90 días)', 'Crítica (+90 días)'],
                    datasets: [{
                        label: 'Vehículos',
                        data: [@json($normal), @json($alerta60),
                            @json($alerta90)
                        ],
                        backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'],
                        borderRadius: 7,
                        maxBarThickness: 75
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
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
            const input = document.getElementById('antBuscar'),
                select = document.getElementById('antNivel'),
                rows = [...document.querySelectorAll('#antTabla tbody tr[data-search]')],
                count = document.getElementById('antContador');

            function filter() {
                let n = 0;
                rows.forEach(r => {
                    let show = r.dataset.search.includes(input.value.trim().toLowerCase()) && (!select
                        .value || r.dataset.nivel === select.value);
                    r.hidden = !show;
                    if (show) n++
                });
                count.textContent = `Mostrando ${n} de ${rows.length}`;
            }
            input.addEventListener('input', filter);
            select.addEventListener('change', filter);
            document.getElementById('antLimpiar').addEventListener('click', () => {
                input.value = '';
                select.value = '';
                filter()
            });
            filter()
        });
    </script>
@endsection
