@extends('layouts.app')
@section('title', 'Conciliación Financiera - COFIHO V2')
@section('page-title', 'Reporte de Conciliación Financiera')
@section('content')
@php
    $totalRegistros = $transacciones->count();
    $conciliadas = $transacciones->filter(fn($t) => strtoupper(trim((string) $t->estado_conciliacion)) === 'CONCILIADA')->count();
    $pendientes = $totalRegistros - $conciliadas;
    $totalVentas = (float) $transacciones->sum('total_venta');
    $totalPagado = (float) $transacciones->sum('total_pagado');
    $diferencia = (float) $transacciones->sum('diferencia');
    $porcentaje = $totalRegistros ? round($conciliadas / $totalRegistros * 100, 1) : 0;
    $datosGrafica = $transacciones->map(fn($t) => [
        'venta' => 'Venta #' . $t->id_venta,
        'total_venta' => (float) $t->total_venta,
        'total_pagado' => (float) $t->total_pagado,
        'diferencia' => (float) $t->diferencia,
    ])->values();
@endphp
<style>
.cf-head{display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;margin-bottom:22px}
.cf-kicker{font-size:11px;font-weight:800;letter-spacing:1.3px;color:#2563eb;text-transform:uppercase}
.cf-head h2{margin:6px 0;font-size:25px;color:#0f172a}
.cf-sub{font-size:13px;color:#64748b;line-height:1.6;margin:5px 0 0}
.cf-back{border:1px solid #cbd5e1;border-radius:10px;padding:10px 15px;text-decoration:none;color:#334155;background:#fff;font-size:13px;font-weight:700}
.cf-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(185px,1fr));gap:13px;margin-bottom:20px}
.cf-stat{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:18px 20px;min-width:0}
.cf-stat small{display:block;color:#64748b;font-size:12px;margin-bottom:8px}
.cf-stat strong{display:block;font-size:clamp(19px,2vw,26px);font-weight:800;color:#0f172a;overflow-wrap:anywhere}
.cf-stat span{display:block;margin-top:7px;color:#64748b;font-size:11px}
.cf-stat.good strong{color:#15803d}.cf-stat.attention strong{color:#b45309}
.cf-card{background:#fff;border:1px solid #e2e8f0;border-radius:14px;padding:22px;margin-bottom:20px}
.cf-card h3{font-size:17px;margin:0 0 5px;color:#0f172a}
.cf-progress{height:9px;background:#e2e8f0;border-radius:20px;overflow:hidden;margin-top:13px}
.cf-progress>div{height:100%;background:#16a34a;border-radius:20px}
.cf-chart{height:300px;position:relative}
.cf-filter{display:flex;flex-wrap:wrap;gap:12px;align-items:end;margin:20px 0 14px}
.cf-filter label{display:block;font-size:12px;font-weight:700;color:#475569;margin-bottom:6px}
.cf-filter input,.cf-filter select{background:#fff;border:1px solid #cbd5e1;border-radius:9px;padding:10px 12px;color:#0f172a;min-width:175px;max-width:100%}
.cf-filter button{background:#f8fafc;border:1px solid #cbd5e1;border-radius:9px;padding:10px 15px;cursor:pointer;color:#334155}
.cf-table-wrap{overflow-x:auto}
.cf-table{width:100%;border-collapse:collapse;font-size:13px;min-width:880px}
.cf-table th{background:#f8fafc;color:#475569;text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:.4px;white-space:nowrap;padding:13px 12px;border-bottom:1px solid #e2e8f0}
.cf-table td{padding:14px 12px;border-bottom:1px solid #eef2f7;color:#334155;vertical-align:middle}
.cf-table tbody tr:hover{background:#f8fafc}
.cf-money{text-align:right;white-space:nowrap;font-variant-numeric:tabular-nums}
.cf-pill{display:inline-block;padding:5px 10px;border-radius:20px;font-size:11px;font-weight:800;white-space:nowrap}
.cf-pill.ok{background:#dcfce7;color:#166534}.cf-pill.warn{background:#fef3c7;color:#92400e}
.cf-link{color:#2563eb;font-weight:700;text-decoration:none}.cf-link:hover{text-decoration:underline}
.cf-empty{text-align:center;padding:28px;color:#64748b}
.cf-counter{font-size:12px;color:#64748b;margin-left:auto}
@media(max-width:700px){.cf-card{padding:16px}.cf-chart{height:260px}.cf-head h2{font-size:21px}}
</style>
<div class="cf-head">
    <div><div class="cf-kicker">Finanzas / Control</div><h2>Conciliación financiera</h2><p class="cf-sub">Comparación de ventas y pagos registrados en Oracle 11g.</p></div>
    <a class="cf-back" href="{{ route('reportes.index') }}">← Centro de reportes</a>
</div>
<div class="cf-grid">
    <div class="cf-stat"><small>Ventas analizadas</small><strong>{{ $totalRegistros }}</strong><span>Registros de conciliación</span></div>
    <div class="cf-stat good"><small>Ventas conciliadas</small><strong>{{ $conciliadas }}</strong><span>{{ number_format($porcentaje, 1) }}% de los registros</span></div>
    <div class="cf-stat attention"><small>Sin conciliar</small><strong>{{ $pendientes }}</strong><span>Estados diferentes de CONCILIADA</span></div>
    <div class="cf-stat"><small>Total de ventas</small><strong>Q {{ number_format($totalVentas, 2) }}</strong><span>Según vista financiera</span></div>
    <div class="cf-stat"><small>Total pagado</small><strong>Q {{ number_format($totalPagado, 2) }}</strong><span>Pagos contabilizados</span></div>
    <div class="cf-stat {{ abs($diferencia) > 0.005 ? 'attention' : 'good' }}"><small>Diferencia acumulada</small><strong>Q {{ number_format($diferencia, 2) }}</strong><span>Suma de diferencias de la vista</span></div>
</div>
<div class="cf-card">
    <h3>Progreso de conciliación</h3>
    <p class="cf-sub">{{ $conciliadas }} de {{ $totalRegistros }} ventas conciliadas.</p>
    <div class="cf-progress" role="progressbar" aria-label="Porcentaje de conciliación" aria-valuenow="{{ $porcentaje }}" aria-valuemin="0" aria-valuemax="100"><div style="width:{{ $porcentaje }}%"></div></div>
</div>
<div class="cf-card">
    <h3>Comparación de ventas y pagos</h3>
    <p class="cf-sub">Montos expresados en quetzales (Q). La diferencia se muestra como serie independiente.</p>
    @if($transacciones->isNotEmpty())
        <div style="overflow-x:auto;margin-top:18px"><div class="cf-chart" style="min-width:{{ max(560, $totalRegistros * 95) }}px"><canvas id="cfGrafica"></canvas></div></div>
    @else
        <div class="cf-empty">No hay datos para graficar.</div>
    @endif
</div>
<div class="cf-card">
    <h3>Detalle de conciliación</h3>
    <p class="cf-sub">Consulta cada venta, los pagos registrados y su estado financiero.</p>
    <div class="cf-filter">
        <div><label for="cfBuscar">Buscar venta</label><input id="cfBuscar" type="search" placeholder="Número de venta"></div>
        <div><label for="cfEstado">Estado de conciliación</label><select id="cfEstado"><option value="">Todos los estados</option>@foreach($transacciones->pluck('estado_conciliacion')->unique() as $estado)<option value="{{ $estado }}">{{ $estado }}</option>@endforeach</select></div>
        <button id="cfLimpiar" type="button">Limpiar filtros</button>
        <span id="cfContador" class="cf-counter"></span>
    </div>
    <div class="cf-table-wrap"><table class="cf-table" id="cfTabla"><thead><tr>
        <th>Venta</th><th>Fecha</th><th>Estado venta</th><th class="cf-money">Total venta</th><th class="cf-money">Total pagado</th><th class="cf-money">Diferencia</th><th>Conciliación</th><th>Acción</th>
    </tr></thead><tbody>
        @forelse($transacciones as $t)
            @php $ok = strtoupper(trim((string)$t->estado_conciliacion)) === 'CONCILIADA'; @endphp
            <tr data-search="{{ $t->id_venta }}" data-estado="{{ $t->estado_conciliacion }}">
                <td><strong>#{{ $t->id_venta }}</strong></td>
                <td>{{ $t->fecha_venta ?? 'No registrada' }}</td>
                <td>{{ $t->estado_venta }}</td>
                <td class="cf-money">Q {{ number_format((float)$t->total_venta, 2) }}</td>
                <td class="cf-money">Q {{ number_format((float)$t->total_pagado, 2) }}</td>
                <td class="cf-money">Q {{ number_format((float)$t->diferencia, 2) }}</td>
                <td><span class="cf-pill {{ $ok ? 'ok' : 'warn' }}">{{ $t->estado_conciliacion }}</span></td>
                <td><a class="cf-link" href="{{ route('ventas.show', ['id' => $t->id_venta]) }}">Ver venta</a></td>
            </tr>
        @empty
            <tr><td colspan="8" class="cf-empty">No hay conciliaciones registradas.</td></tr>
        @endforelse
    </tbody></table></div>
    <div id="cfSinResultados" class="cf-empty" style="display:none">No hay resultados con estos filtros.</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('cfGrafica');
    if (canvas && window.Chart) {
        const datos = @json($datosGrafica);
        new Chart(canvas, {
            type: 'bar',
            data: { labels: datos.map(x => x.venta), datasets: [
                { label: 'Total venta', data: datos.map(x => x.total_venta), backgroundColor: '#2563eb', borderRadius: 4 },
                { label: 'Total pagado', data: datos.map(x => x.total_pagado), backgroundColor: '#16a34a', borderRadius: 4 },
                { label: 'Diferencia', data: datos.map(x => x.diferencia), backgroundColor: '#f59e0b', borderRadius: 4 }
            ]},
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'top' }, tooltip: { callbacks: { label: x => x.dataset.label + ': Q ' + Number(x.parsed.y).toLocaleString('es-GT', {minimumFractionDigits: 2, maximumFractionDigits: 2}) } } }, scales: { y: { beginAtZero: true, ticks: { callback: v => 'Q ' + Number(v).toLocaleString('es-GT') } } } }
        });
    }
    const buscar = document.getElementById('cfBuscar');
    const estado = document.getElementById('cfEstado');
    const filas = Array.from(document.querySelectorAll('#cfTabla tbody tr[data-search]'));
    const contador = document.getElementById('cfContador');
    const sinResultados = document.getElementById('cfSinResultados');
    function filtrar() {
        let visibles = 0;
        filas.forEach(fila => {
            const coincide = fila.dataset.search.includes(buscar.value.trim()) && (!estado.value || fila.dataset.estado === estado.value);
            fila.hidden = !coincide;
            if (coincide) visibles++;
        });
        contador.textContent = 'Mostrando ' + visibles + ' de ' + filas.length;
        sinResultados.style.display = filas.length > 0 && visibles === 0 ? 'block' : 'none';
    }
    buscar.addEventListener('input', filtrar);
    estado.addEventListener('change', filtrar);
    document.getElementById('cfLimpiar').addEventListener('click', () => { buscar.value = ''; estado.value = ''; filtrar(); });
    filtrar();
});
</script>
@endsection
