@extends('layouts.app')
@section('title', 'Centro de operaciones | COFIHO V2')
@section('page-title', 'Centro de operaciones')
@push('styles')
<style>
.dash-welcome{background:linear-gradient(115deg,#0d1c35,#193c78);color:#fff;border-radius:20px;padding:34px 38px;margin-bottom:24px;position:relative;overflow:hidden}
.dash-welcome:after{content:"";position:absolute;width:300px;height:300px;border:1px solid #ffffff22;border-radius:50%;right:-65px;top:-145px;box-shadow:0 0 0 65px #ffffff08,0 0 0 130px #ffffff05;pointer-events:none}
.dash-eyebrow{font-size:12px;text-transform:uppercase;letter-spacing:.16em;color:#93c5fd;font-weight:800;margin-bottom:13px}
.dash-welcome h2{font-size:clamp(24px,3vw,36px);line-height:1.2;margin:0 0 12px;color:#fff}
.dash-welcome p{color:#cbdaf0;max-width:550px;font-size:14px;line-height:1.7;margin:0}
.dash-heading{font-size:17px;font-weight:800;color:#172337;margin:0 0 15px}
.dash-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(205px,1fr));gap:16px;margin-bottom:28px}
.dash-kpi{background:#fff;border:1px solid #e4eaf3;border-radius:15px;padding:22px;box-shadow:0 5px 18px #13244008;display:flex;flex-direction:column;gap:12px}
.dash-kpi span{color:#64748b;font-size:13px;font-weight:600}.dash-kpi strong{font-size:32px;color:#142b51;letter-spacing:-.05em}
.dash-links{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:12px}
.dash-link{display:flex;align-items:center;justify-content:space-between;gap:10px;text-decoration:none;padding:18px 20px;border:1px solid #e4eaf3;background:white;border-radius:13px;color:#19345b;font-weight:700;font-size:14px;transition:.2s}
.dash-link:hover{border-color:#93b5fa;box-shadow:0 8px 20px #2563eb14;transform:translateY(-2px)}.dash-link span{color:#2563eb;font-size:20px}
@media(max-width:600px){.dash-welcome{padding:26px 22px}.dash-grid{grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}.dash-kpi{padding:16px}.dash-kpi strong{font-size:27px}}
</style>
@endpush
@section('content')
<section class="dash-welcome">
  <div class="dash-eyebrow">COFIHO V2 · ADMIN</div>
  <h2>Centro de operaciones</h2>
  <p>Una vista general de la concesionaria, sus operaciones y equipos.</p>
</section>
<h3 class="dash-heading">Indicadores generales</h3>
<div class="dash-grid">
    <div class="dash-kpi"><span>Vehículos registrados</span><strong>{{ number_format($totalVehiculos) }}</strong></div>
    <div class="dash-kpi"><span>Disponibles</span><strong>{{ number_format($disponibles) }}</strong></div>
    <div class="dash-kpi"><span>Ventas registradas</span><strong>{{ number_format($ventas) }}</strong></div>
    <div class="dash-kpi"><span>Reservas pendientes</span><strong>{{ number_format($reservasPendientes) }}</strong></div>
    <div class="dash-kpi"><span>Clientes</span><strong>{{ number_format($clientes) }}</strong></div>
    <div class="dash-kpi"><span>Inspecciones</span><strong>{{ number_format($inspecciones) }}</strong></div>
    <div class="dash-kpi"><span>Avalúos</span><strong>{{ number_format($avaluos) }}</strong></div>
    <div class="dash-kpi"><span>Usuarios</span><strong>{{ number_format($usuarios) }}</strong></div>
</div>
<h3 class="dash-heading">Accesos rápidos</h3>
<nav class="dash-links" aria-label="Accesos rápidos">
    <a class="dash-link" href="{{ route('vehiculos.index') }}">Vehículos <span aria-hidden="true">↗</span></a>
    <a class="dash-link" href="{{ route('reservas.index') }}">Reservas <span aria-hidden="true">↗</span></a>
    <a class="dash-link" href="{{ route('ventas.index') }}">Ventas <span aria-hidden="true">↗</span></a>
    <a class="dash-link" href="{{ route('finanzas.index') }}">Finanzas <span aria-hidden="true">↗</span></a>
    <a class="dash-link" href="{{ route('reportes.index') }}">Reportes <span aria-hidden="true">↗</span></a>
    <a class="dash-link" href="{{ route('usuarios.index') }}">Usuarios <span aria-hidden="true">↗</span></a>
</nav>
@endsection
