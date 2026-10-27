@extends('layouts.app')

@section('content')
    <style>
        .cr-wrap {
            max-width: 1450px;
            margin: 0 auto;
            padding: 26px 18px;
            color: #17233a
        }

        .cr-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 22px
        }

        .cr-head h1 {
            font-size: 29px;
            font-weight: 800;
            margin: 0;
            color: #17233a
        }

        .cr-head p {
            color: #64748b;
            margin: 5px 0 0
        }

        .cr-link {
            background: #eff4fb;
            color: #254a80;
            text-decoration: none;
            border-radius: 10px;
            padding: 11px 16px;
            font-weight: 700
        }

        .cr-stats {
            display: grid;
            grid-template-columns: repeat(5, minmax(0, 1fr));
            gap: 13px;
            margin-bottom: 20px
        }

        .cr-stat,
        .cr-panel {
            background: #fff;
            border: 1px solid #e5eaf1;
            border-radius: 15px;
            box-shadow: 0 5px 22px rgba(20, 39, 71, .045)
        }

        .cr-stat {
            padding: 19px
        }

        .cr-stat span {
            font-size: 12px;
            font-weight: 700;
            color: #64748b
        }

        .cr-stat strong {
            display: block;
            font-size: 24px;
            color: #17233a;
            margin-top: 8px
        }

        .cr-panel {
            padding: 20px;
            margin-bottom: 20px
        }

        .cr-panel h2 {
            font-size: 18px;
            margin: 0 0 16px
        }

        .cr-filters {
            display: flex;
            flex-wrap: wrap;
            align-items: end;
            gap: 13px
        }

        .cr-field {
            display: flex;
            flex-direction: column;
            gap: 6px
        }

        .cr-field label {
            font-size: 12px;
            font-weight: 700;
            color: #64748b
        }

        .cr-field input {
            border: 1px solid #d6dfeb;
            border-radius: 9px;
            padding: 10px 12px;
            min-height: 42px;
            max-width: 220px
        }

        .cr-btn {
            background: #173a69;
            color: #fff;
            border: 0;
            border-radius: 9px;
            padding: 11px 18px;
            cursor: pointer;
            font-weight: 700
        }

        .cr-table-scroll {
            overflow-x: auto
        }

        .cr-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 890px
        }

        .cr-table th {
            text-align: left;
            background: #f5f7fb;
            font-size: 12px;
            color: #53637a;
            padding: 13px
        }

        .cr-table td {
            padding: 14px 13px;
            border-bottom: 1px solid #edf0f5;
            font-size: 14px
        }

        .cr-table tr:last-child td {
            border-bottom: 0
        }

        .cr-name {
            font-weight: 700
        }

        .cr-muted {
            color: #7a8798;
            font-size: 12px
        }

        .cr-number {
            text-align: right !important;
            font-variant-numeric: tabular-nums
        }

        .cr-pill {
            display: inline-block;
            background: #e8f5ed;
            color: #207044;
            padding: 5px 9px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 800
        }

        .cr-pill.gray {
            background: #f1f3f6;
            color: #667085
        }

        .cr-empty {
            padding: 25px;
            text-align: center;
            color: #64748b
        }

        .cr-note {
            font-size: 12px;
            color: #64748b;
            margin-top: 12px
        }

        @media(max-width:1000px) {
            .cr-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media(max-width:540px) {
            .cr-stats {
                grid-template-columns: 1fr
            }

            .cr-head h1 {
                font-size: 24px
            }

            .cr-field input {
                max-width: 100%
            }
        }
    </style>
    <div class="cr-wrap">
        <div class="cr-head">
            <div>
                <h1>Reporte de clientes</h1>
                <p>Análisis comercial de compradores y ventas confirmadas</p>
            </div>
            <a class="cr-link" href="{{ route('reportes.index') }}">← Volver a reportes</a>
        </div>

        <form class="cr-panel cr-filters" method="GET" action="{{ route('reportes.clientes') }}">
            <div class="cr-field"><label for="desde">Desde</label><input type="date" id="desde" name="desde"
                    value="{{ $desde }}"></div>
            <div class="cr-field"><label for="hasta">Hasta</label><input type="date" id="hasta" name="hasta"
                    value="{{ $hasta }}"></div>
            <div class="cr-field"><label for="buscar">Cliente, correo o ID</label><input type="search" id="buscar"
                    name="buscar" value="{{ $buscar }}" placeholder="Buscar cliente"></div>
            <button class="cr-btn" type="submit">Aplicar filtros</button>
            <a class="cr-link" href="{{ route('reportes.clientes') }}">Limpiar</a>
        </form>

        @if ($errors->any())
            <div class="cr-panel" style="color:#b42318">{{ $errors->first() }}</div>
        @endif

        <div class="cr-stats">
            <div class="cr-stat"><span>Clientes compradores</span><strong>{{ number_format($compradores) }}</strong></div>
            <div class="cr-stat"><span>Ventas confirmadas</span><strong>{{ number_format($ventasConfirmadas) }}</strong>
            </div>
            <div class="cr-stat"><span>Vehículos vendidos</span><strong>{{ number_format($vehiculosVendidos) }}</strong>
            </div>
            <div class="cr-stat"><span>Ingresos por ventas</span><strong style="font-size:21px">Q
                    {{ number_format($ingresos, 2) }}</strong></div>
            <div class="cr-stat"><span>Clientes recurrentes</span><strong>{{ number_format($recurrentes) }}</strong></div>
        </div>

        <section class="cr-panel">
            <h2>Ranking de clientes por monto de compras</h2>
            <div class="cr-table-scroll">
                <table class="cr-table">
                    <thead>
                        <tr>
                            <th>Cliente</th>
                            <th>Contacto</th>
                            <th>Estado</th>
                            <th class="cr-number">Compras</th>
                            <th class="cr-number">Vehículos</th>
                            <th class="cr-number">Total comprado</th>
                            <th>Última compra</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($registros as $cliente)
                            <tr>
                                <td>
                                    <div class="cr-name">{{ $cliente->nombre }}</div>
                                    <div class="cr-muted">Cliente #{{ $cliente->id_cliente }}</div>
                                </td>
                                <td>{{ $cliente->email ?: 'Sin correo' }}<div class="cr-muted">
                                        {{ $cliente->telefono ?: 'Sin teléfono' }}</div>
                                </td>
                                <td><span
                                        class="cr-pill {{ (int) $cliente->activo === 1 ? '' : 'gray' }}">{{ (int) $cliente->activo === 1 ? 'Activo' : 'Inactivo' }}</span>
                                </td>
                                <td class="cr-number">{{ number_format($cliente->ventas) }}</td>
                                <td class="cr-number">{{ number_format($cliente->vehiculos) }}</td>
                                <td class="cr-number"><strong>Q {{ number_format($cliente->monto, 2) }}</strong></td>
                                <td>{{ $cliente->ultima_compra ? \Carbon\Carbon::parse($cliente->ultima_compra)->format('d/m/Y') : 'Sin compras' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="cr-empty">No se encontraron clientes para los filtros
                                    seleccionados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <p class="cr-note">Se consideran únicamente ventas CONFIRMADAS. Los filtros de fechas aplican a las ventas; los
                clientes sin compras dentro del período permanecen visibles. Los indicadores reflejan los clientes
                encontrados en la búsqueda.</p>
        </section>
    </div>
@endsection
