@extends('layouts.app')
@section('title', 'Centro de Reportes - COFIHO V2')
@section('page-title', 'Reportes del Sistema')
@section('content')
    <style>
        .rg-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 22px
        }

        .rg-kicker {
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.3px;
            text-transform: uppercase;
            color: #2563eb
        }

        .rg-title {
            margin: 6px 0;
            font-size: 24px;
            color: #0f172a;
            font-weight: 800
        }

        .rg-sub {
            font-size: 13px;
            color: #64748b;
            margin: 4px 0 0
        }

        .rg-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 20px
        }

        .rg-stat,
        .rg-panel,
        .rg-link {
            background: #fff;
            border: 1px solid #dbe3ef;
            border-radius: 14px
        }

        .rg-stat {
            padding: 19px 20px;
            min-width: 0
        }

        .rg-stat small {
            display: block;
            font-size: 12px;
            color: #64748b;
            margin-bottom: 10px
        }

        .rg-stat strong {
            display: block;
            font-size: clamp(17px, 2vw, 25px);
            font-weight: 800;
            color: #0f172a;
            overflow-wrap: anywhere
        }

        .rg-stat span {
            display: block;
            color: #64748b;
            font-size: 11px;
            margin-top: 7px
        }

        .rg-panels {
            display: grid;
            grid-template-columns: minmax(0, 1.7fr) minmax(280px, 1fr);
            gap: 16px;
            margin-bottom: 22px
        }

        .rg-panel {
            padding: 22px;
            min-width: 0
        }

        .rg-panel h3 {
            font-size: 16px;
            margin: 0 0 6px;
            color: #0f172a
        }

        .rg-panel p {
            font-size: 12px;
            color: #64748b;
            margin: 0 0 15px
        }

        .rg-chart {
            height: 285px;
            position: relative
        }

        .rg-mini {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            border-bottom: 1px solid #e2e8f0;
            padding: 12px 0;
            font-size: 13px;
            color: #334155
        }

        .rg-mini:last-child {
            border: 0
        }

        .rg-mini b {
            color: #0f172a
        }

        .rg-section {
            font-size: 17px;
            color: #0f172a;
            margin: 22px 0 14px
        }

        .rg-links {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 15px
        }

        .rg-link {
            padding: 22px;
            text-decoration: none;
            display: block;
            transition: transform .15s, box-shadow .15s
        }

        .rg-link:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 24px #152d5214
        }

        .rg-icon {
            width: 39px;
            height: 39px;
            border-radius: 11px;
            background: #eff6ff;
            color: #2563eb;
            display: grid;
            place-items: center;
            font-weight: 800;
            margin-bottom: 14px;
            font-size: 18px
        }

        .rg-link h3 {
            font-size: 16px;
            color: #0f172a;
            margin: 0 0 8px
        }

        .rg-link p {
            font-size: 12px;
            line-height: 1.6;
            color: #64748b;
            min-height: 40px;
            margin: 0 0 15px
        }

        .rg-action {
            color: #2563eb;
            font-size: 12px;
            font-weight: 800
        }

        .rg-note {
            color: #64748b;
            font-size: 12px;
            margin-top: 15px
        }

        @media(max-width:1100px) {
            .rg-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .rg-panels {
                grid-template-columns: 1fr
            }

            .rg-links {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media(max-width:650px) {

            .rg-links,
            .rg-stats {
                grid-template-columns: 1fr
            }

            .rg-panel {
                padding: 16px
            }

            .rg-chart {
                height: 240px
            }
        }
    </style>
    <div class="rg-head">
        <div>
            <div class="rg-kicker">Análisis gerencial / COFIHO V2</div>
            <h2 class="rg-title">Centro de Reportes</h2>
            <p class="rg-sub">Resumen gerencial de ventas, clientes, inventario y conciliación financiera.</p>
        </div>
    </div>
    <div class="rg-stats">
        <div class="rg-stat"><small>Ventas
                confirmadas</small><strong>{{ number_format($indicadores['ventas']) }}</strong><span>Histórico
                registrado</span></div>
        <div class="rg-stat"><small>Monto vendido</small><strong>Q
                {{ number_format($indicadores['ingresos'], 2) }}</strong><span>Total de ventas confirmadas, no flujo de
                caja</span></div>
        <div class="rg-stat"><small>Vehículos en
                FIFO</small><strong>{{ number_format($indicadores['disponibles']) }}</strong><span>Registros de la vista de
                inventario FIFO</span></div>
        <div class="rg-stat"><small>Inventario crítico</small><strong
                style="color:#dc2626">{{ number_format($indicadores['criticos']) }}</strong><span>Más de 90 días en
                inventario</span></div>
    </div>
    <div class="rg-panels">
        <section class="rg-panel">
            <h3>Evolución mensual de ventas</h3>
            <p>Monto de ventas confirmadas por mes, expresado en quetzales.</p>
            @if ($meses->isNotEmpty())
                <div class="rg-chart"><canvas id="rgVentasChart"></canvas></div>
            @else
                <div class="rg-chart" style="display:grid;place-items:center;color:#64748b">No hay ventas confirmadas para
                    graficar.</div>
            @endif
        </section>
        <section class="rg-panel">
            <h3>Estado del inventario</h3>
            <p>Distribución por antigüedad y resumen financiero.</p>
            <div class="rg-mini"><span>Normal (0–60 días)</span><b>{{ $rangos['normal'] }}</b></div>
            <div class="rg-mini"><span>Alerta (61–90 días)</span><b style="color:#b45309">{{ $rangos['alerta'] }}</b></div>
            <div class="rg-mini"><span>Crítico (más de 90 días)</span><b style="color:#dc2626">{{ $rangos['critica'] }}</b>
            </div>
            <div class="rg-mini"><span>Valor del inventario FIFO</span><b>Q
                    {{ number_format($indicadores['valor_inventario'], 2) }}</b></div>
            <div class="rg-mini"><span>Ventas conciliadas</span><b>{{ $indicadores['conciliadas'] }} /
                    {{ $indicadores['total_conciliaciones'] }}</b></div>
        </section>
    </div>
    <h3 class="rg-section">Explorar reportes</h3>
    <div class="rg-links">
        <a class="rg-link" href="{{ route('reportes.inventario') }}">
            <div class="rg-icon">▤</div>
            <h3>Inventario</h3>
            <p>FIFO, antigüedad, distribución por predio y alertas.</p><span class="rg-action">Analizar inventario →</span>
        </a>
        <a class="rg-link" href="{{ route('reportes.ventas') }}">
            <div class="rg-icon">↗</div>
            <h3>Ventas</h3>
            <p>Importes, evolución mensual y desempeño comercial.</p><span class="rg-action">Analizar ventas →</span>
        </a>
        <a class="rg-link" href="{{ route('reportes.clientes') }}">
            <div class="rg-icon">♙</div>
            <h3>Clientes</h3>
            <p>Compradores, recurrencia, vehículos adquiridos y ranking por monto de compras.</p>
            <span class="rg-action">Analizar clientes →</span>
        </a>
        <a class="rg-link" href="{{ route('reportes.conciliacion') }}">
            <div class="rg-icon">▣</div>
            <h3>Finanzas</h3>
            <p>Conciliación de ventas, pagos y diferencias.</p><span class="rg-action">Ver conciliación →</span>
        </a>
    </div>
    <p class="rg-note">Los reportes disponibles se alimentan de los registros de Oracle. El análisis de reservas podrá incorporarse posteriormente.</p>
    @if ($meses->isNotEmpty())
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const canvas = document.getElementById('rgVentasChart');
                if (!canvas || !window.Chart) return;
                new Chart(canvas, {
                    type: 'bar',
                    data: {
                        labels: @json($meses->keys()->values()),
                        datasets: [{
                            label: 'Ventas (Q)',
                            data: @json($meses->values()),
                            backgroundColor: '#2563eb',
                            borderRadius: 7,
                            maxBarThickness: 60
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(c) {
                                        return 'Q ' + Number(c.parsed.y).toLocaleString('es-GT', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(v) {
                                        return 'Q ' + Number(v).toLocaleString('es-GT');
                                    }
                                }
                            }
                        }
                    }
                });
            });
        </script>
    @endif
@endsection
