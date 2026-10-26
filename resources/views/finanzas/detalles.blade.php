@extends('layouts.app')
@section('title', 'Desglose financiero - COFIHO V2')
@section('page-title', 'Detalles Financieros')
@section('content')
    <style>
        .fin-wrap {
            display: grid;
            gap: 20px
        }

        .fin-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap
        }

        .fin-heading h2 {
            font-size: 21px;
            margin: 0 0 6px;
            color: #14233d
        }

        .fin-muted {
            font-size: 13px;
            color: #71809a;
            margin: 0
        }

        .fin-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px
        }

        .fin-stat {
            padding: 20px;
            border: 1px solid #e6ecf5;
            border-radius: 14px;
            background: white;
            box-shadow: 0 4px 16px #14233d06
        }

        .fin-stat .label {
            font-size: 12px;
            color: #71809a;
            font-weight: 700
        }

        .fin-stat .value {
            font-size: 24px;
            font-weight: 800;
            color: #14233d;
            margin-top: 10px
        }

        .fin-stat .hint {
            font-size: 11px;
            color: #8795aa;
            margin-top: 5px
        }

        .fin-panel {
            background: #fff;
            border: 1px solid #e6ecf5;
            border-radius: 15px;
            padding: 22px;
            box-shadow: 0 4px 18px #14233d06
        }

        .fin-panel h3 {
            font-size: 16px;
            margin: 0 0 6px;
            color: #14233d
        }

        .fin-filters {
            display: grid;
            grid-template-columns: 2fr repeat(2, 1fr) auto;
            gap: 12px;
            align-items: end;
            margin: 20px 0 14px;
            padding: 16px;
            background: #f7f9fd;
            border: 1px solid #e6ecf5;
            border-radius: 12px
        }

        .fin-field label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 7px;
            color: #42516b
        }

        .fin-field input,
        .fin-field select {
            width: 100%;
            height: 40px;
            padding: 0 11px;
            border: 1px solid #dbe3ef;
            border-radius: 9px;
            background: white;
            color: #14233d;
            min-width: 0
        }

        .fin-action {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 6px;
            padding: 11px 15px;
            border-radius: 9px;
            background: #2563eb;
            color: white !important;
            text-decoration: none;
            border: 0;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            white-space: nowrap
        }

        .fin-action.secondary {
            background: #edf2fa;
            color: #334664 !important
        }

        .fin-table-scroll {
            overflow-x: auto
        }

        .fin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            min-width: 850px
        }

        .fin-table th {
            padding: 13px 12px;
            background: #f2f5fa;
            color: #536582;
            text-transform: uppercase;
            letter-spacing: .05em;
            text-align: left;
            font-size: 10px;
            white-space: nowrap
        }

        .fin-table td {
            padding: 14px 12px;
            border-bottom: 1px solid #edf1f7;
            color: #34425b
        }

        .fin-table tbody tr:hover {
            background: #f8faff
        }

        .fin-table .money {
            font-weight: 800;
            color: #14233d;
            white-space: nowrap
        }

        .fin-pill {
            display: inline-block;
            border-radius: 99px;
            padding: 5px 10px;
            font-size: 10px;
            font-weight: 800;
            background: #edf2fa;
            color: #536582
        }

        .fin-pill.green {
            background: #dcfce7;
            color: #166534
        }

        .fin-pill.red {
            background: #fee2e2;
            color: #991b1b
        }

        .fin-pill.blue {
            background: #dbeafe;
            color: #1d4ed8
        }

        .fin-pill.amber {
            background: #fef3c7;
            color: #92400e
        }

        .fin-count {
            color: #71809a;
            font-size: 12px;
            margin-bottom: 12px
        }

        .fin-detail-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px
        }

        .fin-detail-item {
            border: 1px solid #e6ecf5;
            background: #f8fafd;
            border-radius: 10px;
            padding: 15px;
            min-width: 0
        }

        .fin-detail-item span {
            display: block;
            color: #71809a;
            font-size: 11px;
            font-weight: 700;
            margin-bottom: 8px
        }

        .fin-detail-item strong {
            display: block;
            color: #14233d;
            font-size: 14px;
            overflow-wrap: anywhere
        }

        .fin-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap
        }

        .fin-empty {
            text-align: center;
            padding: 35px !important;
            color: #71809a !important
        }

        @media(max-width:1100px) {
            .fin-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .fin-filters {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media(max-width:650px) {

            .fin-grid,
            .fin-filters,
            .fin-detail-grid {
                grid-template-columns: 1fr
            }

            .fin-panel {
                padding: 15px
            }
        }
    </style>
    @php $totalDetalles = $detalles->sum('monto'); @endphp
    <div class="fin-wrap">
        <div class="fin-heading">
            <div>
                <h2>Desglose financiero de la venta</h2>
                <p class="fin-muted">Conceptos registrados para la venta asociada a la transacción.</p>
            </div><span class="fin-pill blue">{{ $detalles->count() }} conceptos</span>
        </div>
        <div class="fin-grid" style="grid-template-columns:repeat(2,minmax(0,1fr))">
            <div class="fin-stat">
                <div class="label">Conceptos registrados</div>
                <div class="value">{{ $detalles->count() }}</div>
                <div class="hint">Registros del desglose</div>
            </div>
            <div class="fin-stat">
                <div class="label">Suma de montos registrados</div>
                <div class="value">Q {{ number_format((float) $totalDetalles, 2) }}</div>
                <div class="hint">Suma aritmética, no total neto de la venta</div>
            </div>
        </div>
        <div class="fin-panel">
            <h3>Detalle de conceptos</h3>
            <p class="fin-muted" style="margin-bottom:18px">Información financiera registrada en Oracle.</p>
            <div class="fin-table-scroll">
                <table class="fin-table">
                    <thead>
                        <tr>
                            <th>ID detalle</th>
                            <th>ID venta</th>
                            <th>Concepto</th>
                            <th>Descripción</th>
                            <th>Monto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detalles as $detalle)
                            <tr>
                                <td><strong>#{{ $detalle->id_detalle_fin }}</strong></td>
                                <td>{{ $detalle->id_venta }}</td>
                                <td><span class="fin-pill blue">{{ $detalle->concepto ?? 'No definido' }}</span></td>
                                <td>{{ $detalle->descripcion ?? 'Sin descripción' }}</td>
                                <td class="money">Q {{ number_format((float) $detalle->monto, 2) }}</td>
                            </tr>
                        @empty<tr>
                                <td colspan="5" class="fin-empty">No hay detalles financieros registrados para esta
                                    venta.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="fin-actions"><a class="fin-action secondary" href="{{ url()->previous() }}">Volver a la
                transacción</a><a class="fin-action secondary" href="{{ route('finanzas.index') }}">Volver a Finanzas</a>
        </div>
    </div>
@endsection
