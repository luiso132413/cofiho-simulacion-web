@extends('layouts.app')

@section('title', 'Vehículos - COFIHO V2')
@section('page-title', 'Gestión de Vehículos')

@push('styles')
<style>
.veh-header{display:flex;align-items:center;justify-content:space-between;gap:18px;margin-bottom:24px;flex-wrap:wrap}
.veh-kicker{font-size:11px;font-weight:800;letter-spacing:.13em;text-transform:uppercase;color:#2563eb;margin-bottom:7px}
.veh-heading{font-size:24px;font-weight:800;letter-spacing:-.04em;color:#14233e;margin:0 0 7px}
.veh-subtitle{font-size:13px;color:#64748b;margin:0}
.veh-header-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
.veh-add-button{display:inline-flex;align-items:center;justify-content:center;gap:9px;padding:11px 16px;border:0;border-radius:10px;background:#2563eb;color:white;font:700 13px inherit;box-shadow:0 5px 14px rgba(37,99,235,.16);opacity:.65;cursor:not-allowed}
.veh-add-button svg{width:17px;height:17px}
.veh-pill{background:#eff6ff;border:1px solid #dbeafe;color:#1d4ed8;border-radius:99px;padding:9px 14px;font-size:12px;font-weight:700}
.veh-kpis{display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:14px;margin-bottom:22px}
.veh-kpi{background:#fff;border:1px solid #e4eaf3;border-radius:15px;padding:19px 20px;box-shadow:0 4px 20px rgba(15,23,42,.035);position:relative;overflow:hidden}
.veh-kpi:before{content:'';position:absolute;left:0;top:0;bottom:0;width:4px;background:var(--accent,#2563eb)}
.veh-kpi-label{color:#64748b;font-size:12px;font-weight:650;margin-bottom:13px}
.veh-kpi-value{font-size:28px;font-weight:800;color:#14233e;letter-spacing:-.045em;line-height:1.15}
.veh-kpi-note{color:#94a3b8;font-size:11px;margin-top:9px}
.veh-panel{background:white;border:1px solid #e4eaf3;border-radius:16px;padding:23px;margin-bottom:22px;box-shadow:0 5px 22px rgba(15,23,42,.035)}
.veh-panel-head{display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:20px}
.veh-panel-title{font-size:16px;font-weight:800;color:#14233e;margin:0}
.veh-panel-subtitle{font-size:12px;color:#64748b;margin:6px 0 0}
.veh-count{font-size:12px;color:#64748b;background:#f1f5f9;border-radius:99px;padding:7px 12px}
.veh-predios{display:grid;grid-template-columns:repeat(auto-fit,minmax(210px,1fr));gap:12px}
.veh-predio{padding:17px;border:1px solid #e2e8f0;border-radius:12px;background:#f8fafc}
.veh-predio-name{font-size:12px;color:#64748b;margin-bottom:10px;min-height:30px}
.veh-predio-bottom{display:flex;align-items:center;justify-content:space-between;gap:12px}
.veh-predio-number{font-size:24px;font-weight:800;color:#14233e}
.veh-track{height:6px;border-radius:20px;background:#e2e8f0;overflow:hidden;margin-top:13px}
.veh-track-fill{height:100%;border-radius:20px;background:#2563eb}
.veh-panel .table-responsive{overflow-x:auto}
.veh-table{width:100%;border-collapse:collapse;font-size:12px;min-width:1060px}
.veh-table th{font-size:10px;text-transform:uppercase;letter-spacing:.07em;font-weight:800;color:#64748b;background:#f5f7fb;padding:14px 12px;white-space:nowrap}
.veh-table td{padding:15px 12px;color:#334155;border-bottom:1px solid #edf1f6;vertical-align:middle}
.veh-table tbody tr:hover td{background:#f8fbff}
.veh-table .veh-name{font-weight:800;color:#14233e;white-space:nowrap}
.veh-table .veh-vin{font-family:ui-monospace,SFMono-Regular,Consolas,monospace;font-size:11px;color:#64748b}
.veh-table .veh-price{font-weight:800;color:#14233e;white-space:nowrap}
.veh-table .veh-muted{color:#94a3b8}
.veh-table .veh-condition{display:inline-block;border-radius:7px;background:#f1f5f9;color:#475569;font-size:10px;font-weight:800;padding:6px 9px}
.veh-table .veh-condition.used{background:#fff7ed;color:#9a3412}
.veh-empty{text-align:center!important;padding:40px!important;color:#64748b!important}
.veh-panel .filtros{background:#f8fafc;border-color:#e5eaf2;border-radius:12px;margin-bottom:18px}
@media(max-width:1050px){.veh-kpis{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:600px){.veh-kpis{grid-template-columns:1fr 1fr;gap:9px}.veh-kpi{padding:15px}.veh-kpi-value{font-size:22px}.veh-panel{padding:16px}.veh-heading{font-size:21px}}
</style>
@endpush

@section('content')
@php
    $totalVehiculos = $vehiculos->count();
    $totalReservados = $vehiculos->filter(fn($v) => strtoupper(trim((string) $v->estado)) === 'RESERVADO')->count();
    $totalVendidos = $vehiculos->filter(fn($v) => strtoupper(trim((string) $v->estado)) === 'VENDIDO')->count();
    $valorDisponible = $vehiculos->filter(fn($v) => strtoupper(trim((string) $v->estado)) === 'DISPONIBLE')->sum(fn($v) => (float) ($v->precio_lista ?? 0));
@endphp

<div class="veh-header">
    <div>
        <div class="veh-kicker">Catálogo automotriz</div>
        <h2 class="veh-heading">Control de vehículos</h2>
        <p class="veh-subtitle">Consulta el catálogo, su estado comercial y la ubicación actual de cada unidad.</p>
    </div>
    <div class="veh-header-actions">
        <span class="veh-pill">{{ number_format($totalVehiculos) }} vehículos registrados</span>
        @if(auth()->user()?->rol?->nombre === 'ADMINISTRADOR')
            {{-- Pendiente de conectar a la ruta y formulario de registro. --}}
            <button type="button" class="veh-add-button" disabled title="Registro de vehículos pendiente de implementar">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 5v14M5 12h14"/></svg>
                Registrar vehículo
            </button>
        @endif
    </div>
</div>

<div class="veh-kpis">
    <div class="veh-kpi" style="--accent:#2563eb">
        <div class="veh-kpi-label">Disponibles en predio</div>
        <div class="veh-kpi-value">{{ number_format($stockTotal) }}</div>
        <div class="veh-kpi-note">Unidades disponibles con ubicación vigente</div>
    </div>
    <div class="veh-kpi" style="--accent:#f59e0b">
        <div class="veh-kpi-label">Vehículos reservados</div>
        <div class="veh-kpi-value">{{ number_format($totalReservados) }}</div>
        <div class="veh-kpi-note">Según estado del vehículo</div>
    </div>
    <div class="veh-kpi" style="--accent:#10b981">
        <div class="veh-kpi-label">Vehículos vendidos</div>
        <div class="veh-kpi-value">{{ number_format($totalVendidos) }}</div>
        <div class="veh-kpi-note">Según estado del vehículo</div>
    </div>
    <div class="veh-kpi" style="--accent:#8b5cf6">
        <div class="veh-kpi-label">Valor de lista disponible</div>
        <div class="veh-kpi-value" style="font-size:clamp(19px,2vw,27px)">Q {{ number_format($valorDisponible, 2) }}</div>
        <div class="veh-kpi-note">Suma de precios de lista; no ingresos</div>
    </div>
</div>

<div class="veh-panel">
    <div class="veh-panel-head">
        <div><h3 class="veh-panel-title">Disponibilidad por predio</h3><p class="veh-panel-subtitle">Distribución de unidades disponibles con ubicación vigente.</p></div>
        <span class="veh-count">{{ $stockPorPredio->count() }} predios con stock</span>
    </div>
    <div class="veh-predios">
        @forelse($stockPorPredio as $predio)
            <div class="veh-predio">
                <div class="veh-predio-name">{{ $predio['predio'] }}</div>
                <div class="veh-predio-bottom"><span class="veh-predio-number">{{ $predio['stock'] }}</span><span class="veh-count">unidades</span></div>
                <div class="veh-track"><div class="veh-track-fill" style="width:{{ $stockTotal > 0 ? min(100, ($predio['stock'] / $stockTotal) * 100) : 0 }}%"></div></div>
            </div>
        @empty
            <p class="veh-subtitle">No hay vehículos disponibles asignados a predios.</p>
        @endforelse
    </div>
</div>

<div class="veh-panel">
    <div class="veh-panel-head">
        <div><h3 class="veh-panel-title">Catálogo de vehículos</h3><p class="veh-panel-subtitle">Filtra por ubicación, marca, tipo, condición o estado.</p></div>
        <span class="veh-count">{{ number_format($totalVehiculos) }} registros</span>
    </div>
    <div class="table-responsive">
        <table class="veh-table" data-filtrable data-filtros="Predio,Marca,Tipo de vehículo,Condición,Estado">
            <thead><tr>
                <th>ID Predio</th><th>Predio</th><th>ID Vehículo</th><th>VIN</th><th>Marca</th><th>Modelo</th><th>Tipo de vehículo</th><th>Año</th><th>Condición</th><th>Precio</th><th>Estado</th>
            </tr></thead>
            <tbody>
            @forelse($vehiculos as $vehiculo)
                <tr>
                    <td class="veh-muted">{{ $vehiculo->inventarioActual?->id_predio ?? '—' }}</td>
                    <td>{{ $vehiculo->inventarioActual?->predio?->nombre ?? 'Sin predio' }}</td>
                    <td>{{ $vehiculo->id_vehiculo }}</td>
                    <td class="veh-vin">{{ $vehiculo->vin }}</td>
                    <td class="veh-name">{{ $vehiculo->modelo?->marca?->nombre ?? 'Sin marca' }}</td>
                    <td class="veh-name">{{ $vehiculo->modelo?->nombre ?? 'Sin modelo' }}</td>
                    <td>{{ $vehiculo->tipoVehiculo?->nombre ?? 'Sin tipo' }}</td>
                    <td>{{ $vehiculo->anio }}</td>
                    <td><span class="veh-condition {{ strtoupper((string)$vehiculo->condicion) === 'USADO' ? 'used' : '' }}">{{ $vehiculo->condicion }}</span></td>
                    <td class="veh-price">Q {{ number_format((float) ($vehiculo->precio_lista ?? 0), 2) }}</td>
                    <td><x-estado :valor="$vehiculo->estado" /></td>
                </tr>
            @empty
                <tr><td colspan="11" class="veh-empty">No hay vehículos registrados.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
