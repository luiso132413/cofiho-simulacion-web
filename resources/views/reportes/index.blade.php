
@extends('layouts.app')

@section('title', 'Reportes - COFIHO V2')

@section('page-title', 'Reportes del Sistema')

@section('content')

<div class="card" style="margin-bottom:20px;">

    <h2>Centro de Reportes</h2>

    <p style="margin-bottom:20px; color:#64748b;">
        Consulta los reportes operativos y financieros
        generados a partir de la base de datos Oracle.
    </p>

    <div style="display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:20px;">

        <div style="padding:20px; border:1px solid #e2e8f0; border-radius:10px;">

            <h3>Inventario FIFO</h3>

            <p style="margin:15px 0; color:#64748b;">
                Consulta los vehículos del inventario
                según su antigüedad de ingreso.
            </p>

            <a href="{{ route('reportes.fifo') }}" class="btn">
                Ver Reporte FIFO
            </a>

        </div>

        <div style="padding:20px; border:1px solid #e2e8f0; border-radius:10px;">

            <h3>Conciliación Financiera</h3>

            <p style="margin:15px 0; color:#64748b;">
                Consulta la información financiera
                para revisar la conciliación de ventas y pagos.
            </p>

            <a href="{{ route('reportes.conciliacion') }}" class="btn">
                Ver Conciliación
            </a>

        </div>

        <div style="padding:20px; border:1px solid #e2e8f0; border-radius:10px;">

            <h3>Antigüedad del Inventario</h3>

            <p style="margin:15px 0; color:#64748b;">
                Analiza la antigüedad de los vehículos
                y su distribución dentro del inventario.
            </p>

            <a href="{{ route('reportes.antiguedad') }}" class="btn">
                Ver Antigüedad
            </a>

        </div>

    </div>

</div>

@endsection
