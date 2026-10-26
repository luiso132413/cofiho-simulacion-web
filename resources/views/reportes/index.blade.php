@extends('layouts.app')

@section('title', 'Reportes - COFIHO V2')

@section('page-title', 'Reportes del Sistema')

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
    <div class="report-head">
        <div>
            <div class="report-kicker">Análisis gerencial</div>
            <h2 style="margin:6px 0">Centro de Reportes</h2>
            <p class="report-sub">Consulta los indicadores de inventario, ventas y finanzas.</p>
        </div>
    </div>
    <div class="report-stat-grid">
        <div class="report-stat"><small>Áreas de análisis</small><strong>3</strong><em>Inventario · Ventas · Finanzas</em>
        </div>
        <div class="report-stat"><small>Inventario</small><strong>FIFO</strong><em>Prioridad y antigüedad</em></div>
        <div class="report-stat"><small>Ventas</small><strong>Q</strong><em>Resultados comerciales</em></div>
        <div class="report-stat"><small>Finanzas</small><strong>✓</strong><em>Conciliación financiera</em></div>
    </div>
    <div class="report-grid">
        <article class="report-tile"><span class="report-icon">▤</span>
            <h3>Inventario</h3>
            <p>Consulta el orden FIFO, la antigüedad, los predios y las alertas por permanencia.</p><a class="btn"
                href="{{ route('reportes.inventario') }}">Analizar inventario →</a>
        </article>
        <article class="report-tile"><span class="report-icon">↗</span>
            <h3>Ventas</h3>
            <p>Analiza el volumen vendido, importes, evolución mensual y actividad por vendedor.</p><a class="btn"
                href="{{ route('reportes.ventas') }}">Analizar ventas →</a>
        </article>
        <article class="report-tile"><span class="report-icon">▣</span>
            <h3>Finanzas</h3>
            <p>Revisa ventas, pagos, diferencias y estados de conciliación.</p><a class="btn"
                href="{{ route('reportes.conciliacion') }}">Ver conciliación →</a>
        </article>
    </div>
@endsection
