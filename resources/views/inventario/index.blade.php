@extends('layouts.app')

@section('title', 'Inventario - COFIHO V2')
@section('page-title', 'Gestión de Inventario')

@push('styles')
<style>
    .inv-page{--inv-navy:#14233d;--inv-muted:#64748b;--inv-line:#e5ebf3}
    .inv-page .inv-header{display:flex;justify-content:space-between;align-items:center;gap:18px;flex-wrap:wrap;margin-bottom:24px}
    .inv-page .inv-kicker{color:#2563eb;text-transform:uppercase;letter-spacing:.13em;font-size:11px;font-weight:800;margin-bottom:8px}
    .inv-page .inv-heading{font-size:clamp(22px,2vw,29px);font-weight:800;letter-spacing:-.035em;color:var(--inv-navy);margin:0 0 8px}
    .inv-page .inv-desc{color:var(--inv-muted);font-size:14px;line-height:1.6;margin:0;max-width:700px}
    .inv-page .inv-count{background:#eff6ff;color:#1d4ed8;border:1px solid #dbeafe;padding:10px 14px;border-radius:12px;font-size:12px;font-weight:700;white-space:nowrap}
    .inv-page .inv-metrics{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin-bottom:22px}
    .inv-page .inv-metric{background:#fff;border:1px solid var(--inv-line);border-radius:15px;padding:20px;box-shadow:0 4px 16px rgba(15,23,42,.035);min-width:0}
    .inv-page .inv-metric-top{display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:15px}
    .inv-page .inv-metric-label{font-size:12px;font-weight:700;color:#64748b}
    .inv-page .inv-metric-icon{width:35px;height:35px;display:grid;place-items:center;border-radius:10px;background:#eff6ff;color:#2563eb;font-size:17px}
    .inv-page .inv-metric-value{font-size:29px;font-weight:800;color:#14233d;letter-spacing:-.04em;line-height:1.1}
    .inv-page .inv-metric-foot{font-size:11px;color:#94a3b8;margin-top:8px}
    .inv-page .inv-panel{background:#fff;border:1px solid var(--inv-line);border-radius:16px;padding:22px;margin-bottom:22px;box-shadow:0 4px 16px rgba(15,23,42,.035)}
    .inv-page .inv-panel-title{font-size:16px;font-weight:800;color:#14233d;margin:0 0 6px}
    .inv-page .inv-panel-desc{font-size:12px;color:#64748b;margin:0 0 18px}
    .inv-page .inv-predios{display:grid;grid-template-columns:repeat(auto-fit,minmax(190px,1fr));gap:12px}
    .inv-page .inv-predio{background:#f8fafc;border:1px solid #e8edf5;border-radius:12px;padding:15px}
    .inv-page .inv-predio-name{font-size:12px;color:#475569;font-weight:700;min-height:32px}
    .inv-page .inv-predio-number{font-size:24px;color:#14233d;font-weight:800;margin:6px 0}
    .inv-page .inv-predio-bar{height:6px;background:#e2e8f0;border-radius:20px;overflow:hidden}
    .inv-page .inv-predio-fill{height:100%;background:#2563eb;border-radius:20px}
    .inv-page .inv-table-title{display:flex;align-items:center;justify-content:space-between;gap:10px;flex-wrap:wrap;margin-bottom:16px}
    .inv-page .inv-table-title .inv-panel-title{margin:0}
    .inv-page .table-responsive{border:1px solid var(--inv-line);border-radius:12px;overflow-x:auto}
    .inv-page table{min-width:950px;width:100%;border-collapse:collapse;font-size:13px}
    .inv-page th{background:#f4f7fb;color:#64748b;text-transform:uppercase;letter-spacing:.055em;font-size:10px;font-weight:800;padding:15px 13px;white-space:nowrap}
    .inv-page td{padding:15px 13px;border-bottom:1px solid #edf1f6;vertical-align:middle;color:#334155}
    .inv-page tbody tr:last-child td{border-bottom:0}
    .inv-page tbody tr:hover td{background:#f8fbff}
    .inv-page .inv-vin{font-weight:700;color:#14233d;font-size:12px;white-space:nowrap}
    .inv-page .inv-secondary{display:block;font-size:11px;color:#94a3b8;margin-top:4px}
    .inv-page .inv-date{white-space:nowrap;font-variant-numeric:tabular-nums}
    .inv-page .inv-empty{text-align:center;padding:38px 14px;color:#64748b}
    @media(max-width:1100px){.inv-page .inv-metrics{grid-template-columns:repeat(2,minmax(0,1fr))}}
    @media(max-width:600px){.inv-page .inv-metrics{grid-template-columns:1fr 1fr;gap:9px}.inv-page .inv-metric{padding:14px}.inv-page .inv-metric-value{font-size:24px}.inv-page .inv-panel{padding:15px}.inv-page .inv-count{white-space:normal}}
</style>
@endpush

@section('content')
@php
    // Indicadores calculados desde los registros que ya entrega InventarioController.
    $totalRegistros = $inventarios->count();
    $vigentes = $inventarios->filter(fn ($item) => is_null($item->fecha_salida));
    $historicos = $totalRegistros - $vigentes->count();
    $disponibles = $vigentes->filter(fn ($item) => strtoupper(trim((string) ($item->vehiculo?->estado ?? ''))) === 'DISPONIBLE')->count();
    $reservados = $vigentes->filter(fn ($item) => strtoupper(trim((string) ($item->vehiculo?->estado ?? ''))) === 'RESERVADO')->count();
    $porPredio = $vigentes->filter(fn ($item) => $item->predio !== null)
        ->groupBy(fn ($item) => (string) $item->id_predio)
        ->map(fn ($grupo) => ['nombre' => $grupo->first()->predio->nombre, 'total' => $grupo->count()])
        ->sortByDesc('total')->values();
    $maxPredio = max(1, (int) $porPredio->max('total'));
@endphp
<div class="inv-page">
    <div class="inv-header">
        <div>
            <div class="inv-kicker">Control y trazabilidad</div>
            <h2 class="inv-heading">Inventario de vehículos</h2>
            <p class="inv-desc">Consulta la ubicación de cada vehículo, su disponibilidad y el historial de ingresos y salidas por predio.</p>
        </div>
        <div class="inv-count">{{ $totalRegistros }} registros de inventario</div>
    </div>

    <div class="inv-metrics">
        <div class="inv-metric"><div class="inv-metric-top"><span class="inv-metric-label">Ubicaciones vigentes</span><span class="inv-metric-icon">⌂</span></div><div class="inv-metric-value">{{ $vigentes->count() }}</div><div class="inv-metric-foot">Registros sin fecha de salida</div></div>
        <div class="inv-metric"><div class="inv-metric-top"><span class="inv-metric-label">Disponibles</span><span class="inv-metric-icon">✓</span></div><div class="inv-metric-value">{{ $disponibles }}</div><div class="inv-metric-foot">En registros vigentes</div></div>
        <div class="inv-metric"><div class="inv-metric-top"><span class="inv-metric-label">Reservados</span><span class="inv-metric-icon">◷</span></div><div class="inv-metric-value">{{ $reservados }}</div><div class="inv-metric-foot">En registros vigentes</div></div>
        <div class="inv-metric"><div class="inv-metric-top"><span class="inv-metric-label">Registros históricos</span><span class="inv-metric-icon">↺</span></div><div class="inv-metric-value">{{ $historicos }}</div><div class="inv-metric-foot">Con fecha de salida registrada</div></div>
    </div>

    <section class="inv-panel" aria-label="Distribución por predio">
        <h3 class="inv-panel-title">Distribución actual por predio</h3>
        <p class="inv-panel-desc">Cantidad de registros de ubicación vigente en cada predio, independientemente del estado del vehículo.</p>
        <div class="inv-predios">
            @forelse($porPredio as $predio)
                <div class="inv-predio">
                    <div class="inv-predio-name">{{ $predio['nombre'] }}</div>
                    <div class="inv-predio-number">{{ $predio['total'] }}</div>
                    <div class="inv-predio-bar"><div class="inv-predio-fill" style="width:{{ round($predio['total'] / $maxPredio * 100) }}%"></div></div>
                </div>
            @empty
                <p class="inv-panel-desc">No hay ubicaciones vigentes asociadas a predios.</p>
            @endforelse
        </div>
    </section>

    <section class="inv-panel" aria-label="Historial de inventario">
        <div class="inv-table-title"><h3 class="inv-panel-title">Registros de inventario</h3><span class="inv-count">Vigentes e históricos</span></div>
        <p class="inv-panel-desc">Utiliza la búsqueda y los filtros para consultar los movimientos registrados.</p>
        <div class="table-responsive">
            <table data-filtrable data-filtros="Predio,Estado,Registro">
                <thead><tr><th>ID Inventario</th><th>Vehículo</th><th>Predio</th><th>Fecha de ingreso</th><th>Fecha de salida</th><th>Estado</th><th>Registro</th></tr></thead>
                <tbody>
                @forelse($inventarios as $inventario)
                    @php
                        $estadoVehiculo = strtoupper(trim((string) ($inventario->vehiculo?->estado ?? '')));
                        $tieneSalida = !is_null($inventario->fecha_salida);
                        $estadoMostrado = $estadoVehiculo !== '' ? $estadoVehiculo : 'SIN ESTADO';
                        $registro = $tieneSalida ? 'HISTÓRICO' : 'VIGENTE';
                        $modelo = $inventario->vehiculo?->modelo?->nombre;
                        $marca = $inventario->vehiculo?->modelo?->marca?->nombre;
                    @endphp
                    <tr>
                        <td><strong>#{{ $inventario->id_inventario }}</strong></td>
                        <td><span class="inv-vin">{{ $inventario->vehiculo?->vin ?? 'Sin vehículo' }}</span>@if($marca || $modelo)<span class="inv-secondary">{{ trim(($marca ?? '') . ' ' . ($modelo ?? '')) }}</span>@endif</td>
                        <td>{{ $inventario->predio?->nombre ?? 'Sin predio' }}</td>
                        <td class="inv-date">{{ $inventario->fecha_ingreso ?? 'No registrada' }}</td>
                        <td class="inv-date">{{ $inventario->fecha_salida ?? 'Sin salida' }}</td>
                        <td><x-estado :valor="$estadoMostrado" /></td>
                        <td>@if($tieneSalida)<span class="badge badge-neutral">HISTÓRICO</span>@else<span class="badge badge-info">VIGENTE</span>@endif</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="inv-empty">No hay registros de inventario.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@endsection
