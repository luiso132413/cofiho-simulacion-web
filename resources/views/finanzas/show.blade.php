@extends('layouts.app')
@section('title', 'Finanzas - COFIHO V2')
@section('page-title', 'Detalle de Transacción Financiera')
@section('content')
    <style>
        .fin-wrap {
            max-width: 1400px;
            margin: 22px auto;
            padding: 0 18px;
            color: #142443
        }

        .fin-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 18px
        }

        .fin-head h2 {
            font-size: 21px;
            font-weight: 800;
            margin: 0 0 4px
        }

        .fin-sub {
            font-size: 12px;
            color: #7081a3;
            margin: 0
        }

        .fin-card {
            background: white;
            border: 1px solid #e1e8f4;
            border-radius: 13px;
            padding: 19px;
            margin-bottom: 16px
        }

        .fin-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 16px
        }

        .fin-grid.two {
            grid-template-columns: repeat(2, minmax(0, 1fr))
        }

        .fin-metric {
            background: white;
            border: 1px solid #e1e8f4;
            border-radius: 12px;
            padding: 17px
        }

        .fin-metric small,
        .fin-label {
            display: block;
            font-size: 11px;
            color: #667a9d;
            font-weight: 700;
            margin-bottom: 8px
        }

        .fin-metric strong {
            font-size: 22px;
            color: #0e2247
        }

        .fin-metric p {
            font-size: 11px;
            color: #8190ab;
            margin: 5px 0 0
        }

        .fin-scroll {
            overflow-x: auto
        }

        .fin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px
        }

        .fin-table th {
            background: #f0f4fa;
            color: #435b84;
            text-transform: uppercase;
            letter-spacing: .05em;
            text-align: left;
            font-size: 10px;
            padding: 13px 11px
        }

        .fin-table td {
            padding: 12px 11px;
            border-bottom: 1px solid #edf1f7
        }

        .fin-table tr:last-child td {
            border-bottom: 0
        }

        .fin-pill {
            display: inline-block;
            border-radius: 20px;
            padding: 5px 9px;
            font-weight: 800;
            font-size: 10px;
            background: #e6eeff;
            color: #2256c5
        }

        .fin-pill.ok {
            background: #ddf9e7;
            color: #087943
        }

        .fin-pill.warn {
            background: #fff0cc;
            color: #a65b00
        }

        .fin-pill.bad {
            background: #ffe4e4;
            color: #a92828
        }

        .fin-btn {
            display: inline-block;
            background: #245ce7;
            color: #fff !important;
            text-decoration: none;
            padding: 9px 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700
        }

        .fin-btn.secondary {
            background: #eef2f9;
            color: #1c3764 !important
        }

        .fin-actions {
            display: flex;
            gap: 9px;
            flex-wrap: wrap;
            margin-top: 14px
        }

        .fin-fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px
        }

        .fin-field {
            background: #f7f9fd;
            border: 1px solid #e4ebf6;
            border-radius: 8px;
            padding: 13px;
            overflow-wrap: anywhere
        }

        .fin-field strong {
            font-size: 13px
        }

        .fin-section-title {
            font-size: 15px;
            font-weight: 800;
            margin: 0 0 4px
        }

        .fin-filter {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            align-items: end;
            background: #f7f9fd;
            border: 1px solid #e5ebf6;
            border-radius: 10px;
            padding: 12px;
            margin: 16px 0
        }

        .fin-filter label {
            font-size: 11px;
            color: #54698e;
            font-weight: 700;
            flex: 1;
            min-width: 150px
        }

        .fin-filter input,
        .fin-filter select {
            display: block;
            width: 100%;
            margin-top: 5px;
            border: 1px solid #dce4f0;
            border-radius: 7px;
            padding: 9px;
            background: white;
            color: #1a3155
        }

        .fin-filter button {
            border: 0;
            border-radius: 7px;
            padding: 10px 13px;
            background: #eaf0fa;
            color: #294b7c;
            cursor: pointer
        }

        .fin-note {
            color: #8291ab;
            font-size: 11px
        }

        .fin-num {
            font-variant-numeric: tabular-nums;
            font-weight: 750;
            white-space: nowrap
        }

        @media(max-width:800px) {
            .fin-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .fin-fields {
                grid-template-columns: 1fr
            }
        }

        @media(max-width:500px) {

            .fin-grid,
            .fin-grid.two {
                grid-template-columns: 1fr
            }
        }
    </style>
    @php
        $tipo = strtoupper((string) $transaccion->tipo);
        $estado = strtoupper((string) $transaccion->estado);
    @endphp
    <div class="fin-wrap">
        <div class="fin-head">
            <div>
                <h2>Transacción #{{ $transaccion->id_transaccion }}</h2>
                <p class="fin-sub">Información y trazabilidad de la operación.</p>
            </div><span class="fin-pill {{ $estado === 'APROBADA' ? 'ok' : 'warn' }}">{{ $estado }}</span>
        </div>
        <div class="fin-grid two">
            <div class="fin-metric"><small>Monto de la transacción</small><strong>{{ $tipo === 'DEVOLUCION' ? '− ' : '' }}Q
                    {{ number_format(abs((float) $transaccion->monto), 2) }}</strong>
                <p>{{ $transaccion->moneda ?? 'GTQ' }}</p>
            </div>
            <div class="fin-metric"><small>Tipo de operación</small><strong>{{ $tipo }}</strong>
                <p>{{ $transaccion->fecha_transaccion ? \Carbon\Carbon::parse($transaccion->fecha_transaccion)->format('d/m/Y H:i') : 'Fecha no registrada' }}
                </p>
            </div>
        </div>
        <div class="fin-card">
            <h3 class="fin-section-title">Información de la operación</h3>
            <p class="fin-sub">Identificadores y datos almacenados en Oracle.</p>
            <div class="fin-fields" style="margin-top:14px">
                <div class="fin-field"><span class="fin-label">ID de
                        transacción</span><strong>#{{ $transaccion->id_transaccion }}</strong></div>
                <div class="fin-field"><span class="fin-label">Venta
                        asociada</span><strong>{{ $transaccion->id_venta ? '#' . $transaccion->id_venta : 'No asociada' }}</strong>
                </div>
                <div class="fin-field"><span class="fin-label">Reserva
                        asociada</span><strong>{{ $transaccion->id_reserva ? '#' . $transaccion->id_reserva : 'No asociada' }}</strong>
                </div>
                <div class="fin-field"><span class="fin-label">Estado</span><strong>{{ $estado }}</strong></div>
                <div class="fin-field"><span class="fin-label">Referencia
                        externa</span><strong>{{ $transaccion->referencia_externa ?: 'No registrada' }}</strong></div>
                <div class="fin-field"><span class="fin-label">Fecha de
                        transacción</span><strong>{{ $transaccion->fecha_transaccion ? \Carbon\Carbon::parse($transaccion->fecha_transaccion)->format('d/m/Y H:i:s') : 'No registrada' }}</strong>
                </div>
            </div>
        </div>
        <div class="fin-card">
            <h3 class="fin-section-title">Método de pago</h3>
            <p class="fin-sub">Medio utilizado para procesar esta operación.</p>
            <div class="fin-fields" style="margin-top:14px">
                <div class="fin-field"><span class="fin-label">Nombre del
                        método</span><strong>{{ $transaccion->metodo_pago_nombre ?? 'No disponible' }}</strong></div>
                <div class="fin-field"><span class="fin-label">Categoría del
                        método</span><strong>{{ $transaccion->metodo_pago_tipo ?? 'No disponible' }}</strong></div>
                <div class="fin-field"><span class="fin-label">Identificador
                        interno</span><strong>#{{ $transaccion->id_metodo_pago }}</strong></div>
                <div class="fin-field"><span class="fin-label">Comisión de procesamiento</span><strong>Consultar desglose
                        financiero</strong>
                    <p class="fin-note">La tabla de métodos no almacena una tasa de comisión.</p>
                </div>
            </div>
        </div>
        <div class="fin-actions">
            @if ($transaccion->id_venta)
                <a class="fin-btn" href="{{ route('finanzas.detalles', $transaccion->id_transaccion) }}">Ver desglose
                    financiero</a>
            @endif
            <a class="fin-btn secondary" href="{{ route('finanzas.index') }}">Volver a Finanzas</a>
        </div>
    </div>
@endsection
