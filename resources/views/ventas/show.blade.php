@extends('layouts.app')
@section('title', 'Detalle de Venta - COFIHO V2')
@section('page-title', 'Detalle de Venta')
@section('content')
    @php
        $total = (float) ($venta->total ?? 0);
        $enganche = $venta->reserva
            ? (float) $venta->reserva
                ->transacciones()
                ->where('tipo', 'ENGANCHE')
                ->where('estado', 'APROBADA')
                ->sum('monto')
            : 0;
        $devoluciones = $venta->reserva
            ? (float) $venta->reserva
                ->transacciones()
                ->where('tipo', 'DEVOLUCION')
                ->where('estado', 'APROBADA')
                ->sum('monto')
            : 0;
        $engancheNeto = $enganche - $devoluciones;
        $saldoPagado = (float) $venta->transacciones->where('tipo', 'PAGO')->where('estado', 'APROBADA')->sum('monto');
        $gastos = (float) $venta->detallesFinancieros
            ->whereIn('concepto', ['TITULACION', 'PLACAS', 'TRASPASO'])
            ->sum('monto');
        $ingresoNeto = $total - $gastos;
    @endphp
    <style>
        .vd-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 22px
        }

        .vd-title {
            margin: 0 0 5px;
            font-size: 23px;
            font-weight: 800;
            color: #12213c
        }

        .vd-muted {
            font-size: 13px;
            color: #64748b;
            margin: 0
        }

        .vd-back {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
            background: #edf3ff;
            color: #1d4ed8;
            padding: 11px 15px;
            border-radius: 9px;
            font-weight: 700;
            font-size: 12px
        }

        .vd-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 20px
        }

        .vd-stat,
        .vd-card {
            background: #fff;
            border: 1px solid #e1e8f2;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 3px 12px #13284b08
        }

        .vd-stat small {
            display: block;
            color: #64748b;
            font-size: 12px;
            margin-bottom: 9px
        }

        .vd-stat strong {
            font-size: 23px;
            color: #12213c
        }

        .vd-card {
            margin-bottom: 20px
        }

        .vd-card h2 {
            font-size: 16px;
            font-weight: 800;
            color: #12213c;
            margin: 0 0 18px
        }

        .vd-info {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px
        }

        .vd-info-item {
            background: #f7f9fc;
            padding: 13px;
            border-radius: 10px
        }

        .vd-info-item small {
            display: block;
            color: #64748b;
            font-size: 11px;
            margin-bottom: 6px
        }

        .vd-info-item strong {
            font-size: 13px;
            color: #24344d
        }

        .vd-table-wrap {
            overflow-x: auto
        }

        .vd-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 540px
        }

        .vd-table th {
            text-align: left;
            background: #f3f6fb;
            color: #526782;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: .04em;
            padding: 13px
        }

        .vd-table td {
            border-bottom: 1px solid #e9edf4;
            padding: 14px 13px;
            font-size: 13px;
            color: #24344d
        }

        .vd-table .vd-strong {
            font-weight: 800;
            color: #10213c
        }

        .vd-pill {
            display: inline-block;
            border-radius: 99px;
            padding: 6px 10px;
            background: #dcfce7;
            color: #166534;
            font-size: 11px;
            font-weight: 800
        }

        .vd-pill.other {
            background: #e9eef7;
            color: #475569
        }

        .vd-finance {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px
        }

        @media(max-width:850px) {
            .vd-grid {
                grid-template-columns: 1fr
            }

            .vd-finance {
                grid-template-columns: 1fr
            }
        }

        @media(max-width:550px) {
            .vd-info {
                grid-template-columns: 1fr
            }

            .vd-card {
                padding: 15px
            }
        }
    </style>
    <div class="vd-top">
        <div>
            <h1 class="vd-title">Venta #{{ $venta->id_venta }}</h1>
            <p class="vd-muted">Consulta de operación comercial y desglose financiero.</p>
        </div><a class="vd-back" href="{{ route('ventas.index') }}">← Volver a ventas</a>
    </div>
    <div class="vd-grid">
        <div class="vd-stat"><small>Precio de venta</small><strong>Q {{ number_format($total, 2) }}</strong></div>
        <div class="vd-stat"><small>Enganche neto aplicado</small><strong>Q {{ number_format($engancheNeto, 2) }}</strong>
        </div>
        <div class="vd-stat"><small>Ingreso neto estimado después de gastos</small><strong>Q
                {{ number_format($ingresoNeto, 2) }}</strong></div>
    </div>
    <section class="vd-card">
        <h2>Información de la venta</h2>
        <div class="vd-info">
            <div class="vd-info-item"><small>ID de venta</small><strong>#{{ $venta->id_venta }}</strong></div>
            <div class="vd-info-item"><small>Estado</small><span
                    class="vd-pill {{ strtoupper((string) ($venta->estado ?? '')) === 'CONFIRMADA' ? '' : 'other' }}">{{ $venta->estado ?? 'No definido' }}</span>
            </div>
            <div class="vd-info-item"><small>ID de cliente</small><strong>#{{ $venta->id_cliente }}</strong></div>
            <div class="vd-info-item"><small>ID de usuario</small><strong>{{ $venta->id_usuario ?? 'No asignado' }}</strong>
            </div>
            <div class="vd-info-item"><small>Fecha de
                    venta</small><strong>{{ $venta->fecha_venta ?? 'No registrada' }}</strong></div>
            <div class="vd-info-item"><small>Reserva
                    asociada</small><strong>{{ $venta->reserva ? '#' . $venta->reserva->id_reserva : 'Sin reserva' }}</strong>
            </div>
        </div>
    </section>
    <div class="vd-finance">
        <section class="vd-card">
            <h2>Desglose financiero · RF-05</h2>
            <div class="vd-table-wrap">
                <table class="vd-table">
                    <tbody>
                        <tr>
                            <td>Precio del vehículo</td>
                            <td class="vd-strong">Q {{ number_format($total, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Enganche aprobado</td>
                            <td>Q {{ number_format($enganche, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Devoluciones aprobadas</td>
                            <td>Q {{ number_format($devoluciones, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Enganche neto aplicado</td>
                            <td>Q {{ number_format($engancheNeto, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Saldo pagado</td>
                            <td>Q {{ number_format($saldoPagado, 2) }}</td>
                        </tr>
                        <tr>
                            <td>Gastos asumidos por COFIHO</td>
                            <td>Q {{ number_format($gastos, 2) }}</td>
                        </tr>
                        <tr>
                            <td class="vd-strong">Ingreso neto estimado después de gastos</td>
                            <td class="vd-strong">Q {{ number_format($ingresoNeto, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
        <section class="vd-card">
            <h2>Detalle de gastos</h2>
            <div class="vd-table-wrap">
                <table class="vd-table">
                    <thead>
                        <tr>
                            <th>Concepto</th>
                            <th>Descripción</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($venta->detallesFinancieros as $detalle)
                            <tr>
                                <td>{{ $detalle->concepto }}</td>
                                <td>{{ $detalle->descripcion ?? '-' }}</td>
                                <td>Q {{ number_format((float) $detalle->monto, 2) }}</td>
                        </tr>@empty<tr>
                                <td colspan="3">No hay gastos financieros registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <section class="vd-card">
        <h2>Vehículos de la venta</h2>
        <div class="vd-table-wrap">
            <table class="vd-table">
                <thead>
                    <tr>
                        <th>ID detalle</th>
                        <th>ID vehículo</th>
                        <th>Cantidad</th>
                        <th>Precio lista</th>
                        <th>Precio venta</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($venta->detalles as $detalle)
                        <tr>
                            <td>#{{ $detalle->id_detalle_venta }}</td>
                            <td>#{{ $detalle->id_vehiculo }}</td>
                            <td>{{ $detalle->cantidad ?? 1 }}</td>
                            <td>Q {{ number_format((float) $detalle->precio_lista, 2) }}</td>
                            <td class="vd-strong">Q {{ number_format((float) $detalle->precio_venta, 2) }}</td>
                    </tr>@empty<tr>
                            <td colspan="5">No hay vehículos registrados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
