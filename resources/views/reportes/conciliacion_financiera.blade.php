@extends('layouts.app')

@section('title', 'Conciliación Financiera - COFIHO V2')

@section('page-title', 'Reporte de Conciliación Financiera')

@section('content')

@php
    $datosGrafica = $transacciones->map(function ($transaccion) {
        return [
            'venta' => 'Venta #' . $transaccion->id_venta,
            'total_venta' => (float) $transaccion->total_venta,
            'total_pagado' => (float) $transaccion->total_pagado,
            'diferencia' => (float) $transaccion->diferencia,
        ];
    })->values();
@endphp

<div class="card" style="margin-bottom:20px;">
    <h2>Comparación de Ventas y Pagos</h2>

    <p style="color:#64748b; margin-bottom:20px;">
        Comparación del total de cada venta, el importe pagado y la diferencia
        registrada en la conciliación financiera.
    </p>

    @if($transacciones->isNotEmpty())
        <div style="overflow-x:auto;">
            <div style="position:relative; height:360px; min-width:{{ max(600, $transacciones->count() * 110) }}px;">
                <canvas id="graficaConciliacion"></canvas>
            </div>
        </div>
        <p style="color:#64748b; font-size:12px; margin-top:10px;">
            Importes expresados en quetzales (Q). Si hay muchas ventas, desplázate horizontalmente para verlas todas.
        </p>
    @else
        <p style="color:#64748b;">No hay registros disponibles para mostrar en la gráfica.</p>
    @endif
</div>

<div class="card">

    <h2>Conciliación de Ventas y Pagos</h2>

    <p style="color:#64748b; margin-bottom:20px;">
        Comparación de los importes de ventas con los pagos
        registrados en Oracle, para identificar diferencias
        y verificar el estado de conciliación.
    </p>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID Venta</th>
                    <th>Fecha de Venta</th>
                    <th>Estado Venta</th>
                    <th>Total Venta</th>
                    <th>Total Pagado</th>
                    <th>Diferencia</th>
                    <th>Estado Conciliación</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transacciones as $transaccion)
                    <tr>
                        <td>{{ $transaccion->id_venta }}</td>
                        <td>{{ $transaccion->fecha_venta ?? 'No registrada' }}</td>
                        <td>{{ $transaccion->estado_venta }}</td>
                        <td>Q {{ number_format((float) $transaccion->total_venta, 2) }}</td>
                        <td>Q {{ number_format((float) $transaccion->total_pagado, 2) }}</td>
                        <td>Q {{ number_format((float) $transaccion->diferencia, 2) }}</td>
                        <td>{{ $transaccion->estado_conciliacion }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:20px;">
                            No hay registros de conciliación financiera.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<div style="margin-top:15px;">
    <a href="{{ route('reportes.index') }}" class="btn">
        Volver a Reportes
    </a>
</div>

@if($transacciones->isNotEmpty())
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('graficaConciliacion');
    if (!canvas || typeof Chart === 'undefined') return;

    const registros = @json($datosGrafica);
    const formatoQuetzales = function (valor) {
        return 'Q ' + Number(valor).toLocaleString('es-GT', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    };

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: registros.map(r => r.venta),
            datasets: [
                {
                    label: 'Total venta',
                    data: registros.map(r => r.total_venta),
                    backgroundColor: '#2563eb',
                    borderRadius: 4
                },
                {
                    label: 'Total pagado',
                    data: registros.map(r => r.total_pagado),
                    backgroundColor: '#16a34a',
                    borderRadius: 4
                },
                {
                    label: 'Diferencia',
                    data: registros.map(r => r.diferencia),
                    backgroundColor: '#f59e0b',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            return context.dataset.label + ': ' + formatoQuetzales(context.parsed.y);
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Importe (Q)' },
                    ticks: {
                        callback: function (value) {
                            return 'Q ' + Number(value).toLocaleString('es-GT');
                        }
                    }
                },
                x: { title: { display: true, text: 'ID de venta' } }
            }
        }
    });
});
</script>
@endif

@endsection
