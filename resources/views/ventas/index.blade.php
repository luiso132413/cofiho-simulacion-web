@extends('layouts.app')
@section('title', 'Ventas - COFIHO V2')
@section('page-title', 'Gestión de Ventas')
@section('content')
    @php
        $cantidadVentas = $ventas->count();
        $confirmadas = $ventas->filter(fn($v) => strtoupper((string) ($v->estado ?? '')) === 'CONFIRMADA')->count();
        $totalVentas = $ventas->sum(fn($v) => (float) ($v->total ?? 0));
        $promedio = $cantidadVentas ? $totalVentas / $cantidadVentas : 0;
    @endphp
    <style>
        .cv-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 22px
        }

        .cv-title {
            font-size: 22px;
            font-weight: 800;
            color: #10213c;
            margin: 0 0 5px
        }

        .cv-muted {
            font-size: 13px;
            color: #64748b;
            margin: 0
        }

        .cv-primary {
            background: #2563eb;
            color: white;
            border: 0;
            border-radius: 10px;
            padding: 12px 17px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px
        }

        .cv-primary:hover {
            background: #1d4ed8;
            color: white
        }

        .cv-kpis {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 22px
        }

        .cv-kpi {
            background: white;
            border: 1px solid #e3eaf3;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 3px 12px #13284b08
        }

        .cv-kpi small {
            display: block;
            color: #64748b;
            font-size: 12px;
            margin-bottom: 10px
        }

        .cv-kpi strong {
            display: block;
            font-size: 24px;
            color: #10213c;
            letter-spacing: -.03em
        }

        .cv-panel {
            background: white;
            border: 1px solid #e3eaf3;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 3px 12px #13284b08
        }

        .cv-panel h2 {
            font-size: 16px;
            color: #10213c;
            margin: 0 0 6px;
            font-weight: 800
        }

        .cv-form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 17px;
            margin-top: 20px
        }

        .cv-field label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            color: #34445d;
            margin-bottom: 7px
        }

        .cv-field input,
        .cv-field select,
        .cv-search {
            width: 100%;
            min-height: 43px;
            border: 1px solid #d7e0ed;
            border-radius: 9px;
            padding: 10px 12px;
            background: white;
            color: #15243b;
            font-size: 13px;
            box-sizing: border-box
        }

        .cv-field input:focus,
        .cv-field select:focus,
        .cv-search:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px #2563eb1c
        }

        .cv-note {
            background: #f3f7ff;
            border: 1px solid #dce8ff;
            padding: 12px;
            border-radius: 10px;
            color: #516581;
            font-size: 12px;
            margin-top: 17px
        }

        .cv-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 18px
        }

        .cv-table-wrap {
            overflow-x: auto
        }

        .cv-table {
            width: 100%;
            border-collapse: collapse;
            min-width: 720px
        }

        .cv-table th {
            background: #f3f6fb;
            color: #526782;
            font-size: 10px;
            letter-spacing: .055em;
            text-transform: uppercase;
            text-align: left;
            padding: 14px 12px
        }

        .cv-table td {
            padding: 15px 12px;
            border-bottom: 1px solid #e8edf5;
            font-size: 13px;
            color: #24344d
        }

        .cv-table tbody tr:hover {
            background: #f8faff
        }

        .cv-pill {
            display: inline-flex;
            border-radius: 99px;
            padding: 5px 9px;
            font-size: 10px;
            font-weight: 800;
            background: #dcfce7;
            color: #166534
        }

        .cv-pill.other {
            background: #e9eef7;
            color: #475569
        }

        .cv-link {
            display: inline-block;
            padding: 8px 13px;
            background: #eef4ff;
            color: #1d4ed8;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            font-size: 12px
        }

        .cv-link:hover {
            background: #dce9ff
        }

        .cv-toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 14px;
            margin: 18px 0
        }

        .cv-search {
            max-width: 320px
        }

        .cv-empty {
            text-align: center;
            color: #64748b !important;
            padding: 35px !important
        }

        .cv-alert {
            border-radius: 9px;
            padding: 12px;
            margin-bottom: 14px;
            font-size: 13px
        }

        .cv-error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca
        }

        @media(max-width:950px) {
            .cv-kpis {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media(max-width:600px) {

            .cv-kpis,
            .cv-form-grid {
                grid-template-columns: 1fr
            }

            .cv-panel {
                padding: 15px
            }

            .cv-toolbar {
                flex-direction: column;
                align-items: stretch
            }

            .cv-search {
                max-width: none
            }
        }
    </style>
    <div class="cv-heading">
        <div>
            <h1 class="cv-title">Administración de ventas</h1>
            <p class="cv-muted">Consulta operaciones comerciales y registra nuevas ventas de vehículos.</p>
        </div><button type="button" class="cv-primary" id="cvToggle" aria-expanded="{{ $errors->any() ? 'true' : 'false' }}"
            aria-controls="cvForm">+ Nueva venta</button>
    </div>
    <div class="cv-kpis">
        <div class="cv-kpi"><small>Ventas registradas</small><strong>{{ $cantidadVentas }}</strong></div>
        <div class="cv-kpi"><small>Ventas confirmadas</small><strong>{{ $confirmadas }}</strong></div>
        <div class="cv-kpi"><small>Monto total registrado</small><strong>Q {{ number_format($totalVentas, 2) }}</strong>
        </div>
        <div class="cv-kpi"><small>Ticket promedio</small><strong>Q {{ number_format($promedio, 2) }}</strong></div>
    </div>
    <section class="cv-panel" id="cvForm" @if (!$errors->any()) hidden @endif>
        <h2>Procesar nueva venta</h2>
        <p class="cv-muted">Completa los datos y confirma antes de registrar la operación en Oracle.</p>
        @if ($errors->any())
            <div class="cv-alert cv-error" role="alert" style="margin-top:16px">{{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('ventas.store') }}" id="cvVentaForm">@csrf<div class="cv-form-grid">
                <div class="cv-field"><label for="id_cliente">Cliente</label><select id="id_cliente" name="id_cliente"
                        required>
                        <option value="">Selecciona un cliente activo</option>
                        @foreach ($clientes as $cliente)
                            <option value="{{ $cliente->id_cliente }}" @selected(old('id_cliente') == $cliente->id_cliente)>{{ $cliente->nombre }}
                                {{ $cliente->apellido }} · #{{ $cliente->id_cliente }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="cv-field"><label for="id_vehiculo">ID del vehículo</label><input type="number" min="1"
                        id="id_vehiculo" name="id_vehiculo" value="{{ old('id_vehiculo') }}" placeholder="Ej. 4" required>
                </div>
                <div class="cv-field"><label for="id_metodo_pago">ID del método de pago</label><input type="number"
                        min="1" id="id_metodo_pago" name="id_metodo_pago" value="{{ old('id_metodo_pago') }}"
                        placeholder="ID registrado en Oracle" required></div>
                <div class="cv-field"><label for="id_reserva">ID de reserva (opcional)</label><input type="number"
                        min="1" id="id_reserva" name="id_reserva" value="{{ old('id_reserva') }}"
                        placeholder="Dejar vacío si no aplica"></div>
            </div>
            <div class="cv-note">El vehículo, método de pago y reserva se identifican temporalmente por ID, tal como los
                recibe el controlador actual. Verifica que correspondan antes de confirmar.</div>
            <div class="cv-actions"><button class="cv-primary" type="submit">Procesar venta</button></div>
        </form>
    </section>
    <section class="cv-panel">
        <h2>Historial de ventas</h2>
        <p class="cv-muted">Operaciones registradas en el sistema.</p>
        <div class="cv-toolbar"><input class="cv-search" id="cvSearch" type="search"
                placeholder="Buscar por venta, cliente o estado..." aria-label="Buscar ventas"><span class="cv-muted"
                id="cvCount">{{ $cantidadVentas }} registros</span></div>
        <div class="cv-table-wrap">
            <table class="cv-table">
                <thead>
                    <tr>
                        <th>Venta</th>
                        <th>Cliente ID</th>
                        <th>Vendedor ID</th>
                        <th>Fecha</th>
                        <th>Total</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody id="cvRows">
                    @forelse($ventas as $venta)
                        <tr>
                            <td><strong>#{{ $venta->id_venta }}</strong></td>
                            <td>#{{ $venta->id_cliente }}</td>
                            <td>{{ $venta->id_usuario ? '#' . $venta->id_usuario : 'No asignado' }}</td>
                            <td>{{ $venta->fecha_venta ?? 'No registrada' }}</td>
                            <td><strong>{{ $venta->total !== null ? 'Q ' . number_format((float) $venta->total, 2) : 'No disponible' }}</strong>
                            </td>
                            <td><span
                                    class="cv-pill {{ strtoupper((string) ($venta->estado ?? '')) === 'CONFIRMADA' ? '' : 'other' }}">{{ $venta->estado ?? 'No definido' }}</span>
                            </td>
                            <td><a class="cv-link" href="{{ route('ventas.show', $venta->id_venta) }}">Ver detalle</a></td>
                    </tr>@empty<tr>
                            <td colspan="7" class="cv-empty">No hay ventas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <p class="cv-muted" id="cvNoResults" hidden style="margin-top:16px">No se encontraron ventas con ese criterio.</p>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('cvToggle'),
                panel = document.getElementById('cvForm');
            btn.addEventListener('click', function() {
                panel.hidden = !panel.hidden;
                btn.setAttribute('aria-expanded', String(!panel.hidden));
                if (!panel.hidden) panel.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            });
            const search = document.getElementById('cvSearch'),
                rows = Array.from(document.querySelectorAll('#cvRows tr')).filter(r => !r.querySelector(
                    '[colspan]')),
                count = document.getElementById('cvCount'),
                empty = document.getElementById('cvNoResults');
            search.addEventListener('input', function() {
                const q = this.value.trim().toLocaleLowerCase();
                let shown = 0;
                rows.forEach(r => {
                    const match = r.textContent.toLocaleLowerCase().includes(q);
                    r.hidden = !match;
                    if (match) shown++;
                });
                count.textContent = shown + ' de ' + rows.length + ' registros';
                empty.hidden = shown !== 0 || rows.length === 0;
            });
            document.getElementById('cvVentaForm').addEventListener('submit', function(e) {
                if (!confirm('¿Deseas procesar esta venta? Esta operación registrará cambios en Oracle.')) e
                    .preventDefault();
            });
        });
    </script>
@endsection
