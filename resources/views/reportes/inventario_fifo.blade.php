
@extends('layouts.app')

@section('title', 'Inventario FIFO - COFIHO V2')

@section('page-title', 'Reporte de Inventario FIFO')

@section('content')

@php
    // Resumen exclusivamente visual basado en los registros del reporte FIFO.
    $vehiculosPorPredio = $vehiculos->groupBy('id_predio')
        ->map(fn ($registros) => $registros->count())
        ->sortKeys();

    $etiquetasPredios = $vehiculosPorPredio->keys()
        ->map(fn ($id) => 'Predio ' . $id)
        ->values();

    $cantidadesPredios = $vehiculosPorPredio->values();
@endphp


<div class="card" style="margin-bottom:20px;">
    <h2>Distribución de Vehículos por Predio</h2>

    <p style="color:#64748b; margin-bottom:20px;">
        Cantidad de registros de vehículos por predio incluidos en el reporte FIFO.
    </p>

    @if($vehiculosPorPredio->isNotEmpty())
        <div style="position:relative; height:320px;">
            <canvas id="graficaFifoPredios"></canvas>
        </div>
    @else
        <p style="color:#64748b;">No hay datos para mostrar en la gráfica.</p>
    @endif
</div>

<div class="card">

    <h2>Inventario de Vehículos - Método FIFO</h2>

    <p style="color:#64748b; margin-bottom:20px;">
        Vehículos ordenados según su antigüedad de ingreso
        al inventario, aplicando el método FIFO
        (Primero en Entrar, Primero en Salir).
    </p>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Orden FIFO</th>
                    <th>ID Inventario</th>
                    <th>ID Predio</th>
                    <th>ID Vehículo</th>
                    <th>VIN</th>
                    <th>Condición</th>
                    <th>Estado</th>
                    <th>Precio Lista</th>
                    <th>Fecha Ingreso</th>
                    <th>Días Inventario</th>
                </tr>
            </thead>

            <tbody>
                @forelse($vehiculos->sortBy('orden_fifo') as $vehiculo)
                    <tr>
                        <td>
                            {{ $vehiculo->orden_fifo }}
                        </td>

                        <td>
                            {{ $vehiculo->id_inventario }}
                        </td>

                        <td>
                            {{ $vehiculo->id_predio }}
                        </td>

                        <td>
                            {{ $vehiculo->id_vehiculo }}
                        </td>

                        <td>
                            {{ $vehiculo->vin }}
                        </td>

                        <td>
                            {{ $vehiculo->condicion }}
                        </td>

                        <td>
                            {{ $vehiculo->estado }}
                        </td>

                        <td>
                            Q {{ number_format((float) $vehiculo->precio_lista, 2) }}
                        </td>

                        <td>
                            {{ $vehiculo->fecha_ingreso }}
                        </td>

                        <td>
                            {{ $vehiculo->dias_inventario }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align:center; padding:20px;">
                            No hay vehículos registrados en el reporte FIFO.
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

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('graficaFifoPredios');

    if (!canvas || typeof Chart === 'undefined') {
        return;
    }

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: @json($etiquetasPredios),
            datasets: [{
                label: 'Vehículos',
                data: @json($cantidadesPredios),
                backgroundColor: '#2563eb',
                borderRadius: 6,
                maxBarThickness: 90
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0 },
                    title: {
                        display: true,
                        text: 'Cantidad de vehículos'
                    }
                }
            }
        }
    });
});
</script>

@endsection
