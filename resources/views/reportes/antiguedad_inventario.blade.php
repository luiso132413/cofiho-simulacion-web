
@extends('layouts.app')

@section('title', 'Antigüedad del Inventario - COFIHO V2')

@section('page-title', 'Reporte de Antigüedad del Inventario')

@section('content')

@php
    $normal = $inventario->filter(
        fn($v) => (int) $v->dias_inventario <= 60
    )->count();

    $alerta60 = $inventario->filter(
        fn($v) => (int) $v->dias_inventario > 60
            && (int) $v->dias_inventario <= 90
    )->count();

    $alerta90 = $inventario->filter(
        fn($v) => (int) $v->dias_inventario > 90
    )->count();
@endphp

<div class="card" style="margin-bottom:20px;">

    <h2>Resumen de Antigüedad</h2>

    <p style="color:#64748b;">
        Monitoreo de vehículos en inventario para
        identificar unidades que requieren promociones
        o reubicación física.
    </p>

    <div style="display:flex; gap:15px; flex-wrap:wrap; margin-top:20px;">

        <div style="flex:1; min-width:160px; padding:20px;
                    background:#dcfce7; border-radius:8px;">

            <h3 style="color:#166534;">Normal</h3>

            <h2 style="color:#166534;">{{ $normal }}</h2>

            <p style="color:#166534;">0 a 60 días</p>

        </div>

        <div style="flex:1; min-width:160px; padding:20px;
                    background:#fef3c7; border-radius:8px;">

            <h3 style="color:#92400e;">Alerta</h3>

            <h2 style="color:#92400e;">{{ $alerta60 }}</h2>

            <p style="color:#92400e;">61 a 90 días</p>

        </div>

        <div style="flex:1; min-width:160px; padding:20px;
                    background:#fee2e2; border-radius:8px;">

            <h3 style="color:#991b1b;">Alerta crítica</h3>

            <h2 style="color:#991b1b;">{{ $alerta90 }}</h2>

            <p style="color:#991b1b;">Más de 90 días</p>

        </div>

    </div>

</div>

<div class="card" style="margin-bottom:20px;">

    <h2>Distribución de Antigüedad del Inventario</h2>

    <p style="color:#64748b; margin-bottom:20px;">
        Cantidad de vehículos según el tiempo que llevan
        registrados en el inventario.
    </p>

    <div style="position:relative; height:320px;">
        <canvas id="graficaAntiguedad"></canvas>
    </div>

</div>

<div class="card" style="margin-bottom:20px;">

    <h2>Resumen de Antigüedad por Sucursal</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Sucursal / Predio</th>
                    <th>0-30 días</th>
                    <th>31-60 días</th>
                    <th>61-90 días</th>
                    <th>Más de 90 días</th>
                    <th>Total vehículos</th>
                    <th>Valor inventario</th>
                </tr>
            </thead>

            <tbody>
                @forelse($resumenPredios as $predio)
                    <tr>
                        <td>{{ $predio['predio'] }}</td>
                        <td>{{ $predio['dias_0_30'] }}</td>
                        <td>{{ $predio['dias_31_60'] }}</td>
                        <td>{{ $predio['dias_61_90'] }}</td>
                        <td>{{ $predio['dias_mas_90'] }}</td>
                        <td><strong>{{ $predio['total'] }}</strong></td>
                        <td>
                            Q {{ number_format((float) $predio['valor'], 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center;">
                            No hay inventario disponible.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>


    <h2>Detalle de Vehículos en Inventario</h2>

    <div class="table-responsive">

        <table>
            <thead>
                <tr>
                    <th>ID Predio</th>
                    <th>ID Vehículo</th>
                    <th>VIN</th>
                    <th>Fecha de Ingreso</th>
                    <th>Días</th>
                    <th>Precio de Lista</th>
                    <th>Antigüedad</th>
                    <th>Alerta / Acción sugerida</th>
                </tr>
            </thead>

            <tbody>

                @forelse($inventario->sortByDesc('dias_inventario') as $vehiculo)

                    @php
                        $dias = (int) $vehiculo->dias_inventario;

                        if ($dias > 90) {
                            $color = '#991b1b';
                            $fondo = '#fee2e2';
                            $alerta = 'CRÍTICA';
                            $accion = 'Evaluar reubicación o promoción';
                        } elseif ($dias > 60) {
                            $color = '#92400e';
                            $fondo = '#fef3c7';
                            $alerta = 'PREVENTIVA';
                            $accion = 'Evaluar promoción';
                        } else {
                            $color = '#166534';
                            $fondo = '#dcfce7';
                            $alerta = 'NORMAL';
                            $accion = 'Sin acción requerida';
                        }
                    @endphp

                    <tr>

                        <td>{{ $vehiculo->id_predio }}</td>

                        <td>{{ $vehiculo->id_vehiculo }}</td>

                        <td>{{ $vehiculo->vin }}</td>

                        <td>
                            {{ $vehiculo->fecha_ingreso ?? 'No registrada' }}
                        </td>

                        <td>{{ $dias }}</td>

                        <td>
                            Q {{ number_format((float) $vehiculo->precio_lista, 2) }}
                        </td>

                        <td>{{ $vehiculo->rango_antiguedad }}</td>

                        <td>
                            <span style="
                                display:inline-block;
                                padding:5px 10px;
                                border-radius:6px;
                                background:{{ $fondo }};
                                color:{{ $color }};
                                font-weight:bold;
                                font-size:12px;
                            ">
                                {{ $alerta }}
                            </span>

                            <div style="margin-top:5px; font-size:12px;">
                                {{ $accion }}
                            </div>
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="8" style="text-align:center; padding:20px;">
                            No hay vehículos registrados.
                        </td>
                    </tr>

                @endforelse

            </tbody>
        </table>

    </div>

</div>

<div style="margin-top:20px;">
    <a href="{{ route('reportes.index') }}" class="btn">
        Volver a Reportes
    </a>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('graficaAntiguedad');

    if (!canvas || typeof Chart === 'undefined') {
        return;
    }

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: [
                'Normal (0-60 días)',
                'Alerta (61-90 días)',
                'Crítica (+90 días)'
            ],
            datasets: [{
                label: 'Cantidad de vehículos',
                data: [
                    @json($normal),
                    @json($alerta60),
                    @json($alerta90)
                ],
                backgroundColor: ['#22c55e', '#f59e0b', '#ef4444'],
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
