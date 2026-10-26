@extends('layouts.app')
@section('title', 'Detalle de Inspección - COFIHO V2')
@section('page-title', 'Detalle de Inspección')
@section('content')
    <style>
        .det-wrap {
            color: #14233d
        }

        .det-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 20px
        }

        .det-head h2 {
            margin: 0 0 6px;
            font-size: 22px
        }

        .det-muted {
            color: #71819a;
            font-size: 13px
        }

        .det-btn {
            display: inline-block;
            border: 0;
            background: #2563eb;
            color: #fff;
            padding: 11px 16px;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer
        }

        .det-btn:hover {
            background: #1d4ed8;
            color: #fff
        }

        .det-alt {
            background: #eef3fc;
            color: #23416b
        }

        .det-alt:hover {
            background: #dce8fa;
            color: #23416b
        }

        .det-card {
            background: #fff;
            border: 1px solid #e4eaf4;
            border-radius: 14px;
            padding: 22px;
            margin-bottom: 20px;
            box-shadow: 0 8px 26px #14233d08
        }

        .det-card h3 {
            margin: 0 0 15px;
            font-size: 16px
        }

        .det-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 14px
        }

        .det-item {
            background: #f7f9fd;
            border: 1px solid #e9eef6;
            border-radius: 10px;
            padding: 15px;
            min-width: 0
        }

        .det-item small {
            display: block;
            color: #71819a;
            font-size: 11px;
            margin-bottom: 8px
        }

        .det-item strong {
            font-size: 14px;
            overflow-wrap: anywhere
        }

        .det-pill {
            display: inline-block;
            padding: 6px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 800
        }

        .det-ok {
            background: #dcfce7;
            color: #166534
        }

        .det-wait {
            background: #fef3c7;
            color: #92400e
        }

        .det-no {
            background: #fee2e2;
            color: #991b1b
        }

        .det-table-wrap {
            overflow-x: auto
        }

        .det-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px
        }

        .det-table th {
            background: #f2f5fa;
            color: #64748b;
            text-align: left;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: .05em;
            padding: 12px
        }

        .det-table td {
            padding: 13px 12px;
            border-bottom: 1px solid #e8edf5;
            vertical-align: top
        }

        .det-field label {
            display: block;
            font-weight: 700;
            font-size: 12px;
            margin-bottom: 7px
        }

        .det-field input,
        .det-field select,
        .det-field textarea {
            width: 100%;
            border: 1px solid #d7e0ee;
            border-radius: 8px;
            padding: 10px;
            font-size: 13px;
            background: #fff
        }

        .det-alert {
            padding: 14px;
            border: 1px solid #fecaca;
            border-radius: 10px;
            background: #fef2f2;
            color: #991b1b;
            margin-bottom: 16px
        }

        .det-success {
            background: #ecfdf5;
            color: #166534;
            border-color: #bbf7d0
        }

        .det-notice {
            background: #fffbeb;
            color: #92400e;
            border: 1px solid #fde68a;
            padding: 12px;
            border-radius: 9px;
            margin-top: 15px;
            font-size: 13px
        }

        .det-empty {
            text-align: center;
            color: #71819a;
            padding: 25px !important
        }

        .det-data {
            display: grid;
            gap: 5px
        }

        .det-data div {
            overflow-wrap: anywhere
        }

        @media(max-width:700px) {
            .det-grid {
                grid-template-columns: 1fr
            }

            .det-card {
                padding: 15px
            }
        }
    </style>
    @php $resultado = strtoupper((string)($inspeccion->resultado ?? 'PENDIENTE')); @endphp
    <div class="det-wrap">
        @if (session('success'))
            <div class="det-alert det-success" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="det-alert" role="alert"><strong>No se pudo completar la operación:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="det-head">
            <div>
                <h2>Inspección #{{ $inspeccion->id_inspeccion }}</h2>
                <div class="det-muted">Consulta la evaluación técnica y los avalúos del vehículo.</div>
            </div><a href="{{ route('inspecciones.index') }}" class="det-btn det-alt">← Volver a inspecciones</a>
        </div>
        <section class="det-card">
            <h3>Información de la inspección</h3>
            <div class="det-grid">
                <div class="det-item"><small>Vehículo</small><strong>#{{ $inspeccion->id_vehiculo }}</strong></div>
                <div class="det-item"><small>VIN</small><strong>{{ $inspeccion->vehiculo->vin ?? 'No disponible' }}</strong>
                </div>
                <div class="det-item"><small>Inspector /
                        usuario</small><strong>#{{ $inspeccion->id_usuario ?? 'No asignado' }}</strong></div>
                <div class="det-item"><small>Fecha de
                        inspección</small><strong>{{ $inspeccion->fecha_inspeccion ?? 'No registrada' }}</strong></div>
                <div class="det-item"><small>Resultado</small><span
                        class="det-pill {{ $resultado === 'APROBADA' ? 'det-ok' : ($resultado === 'RECHAZADA' ? 'det-no' : 'det-wait') }}">{{ $resultado }}</span>
                </div>
                <div class="det-item"><small>Observaciones
                        generales</small><strong>{{ $inspeccion->observaciones ?? 'Sin observaciones' }}</strong></div>
            </div>
        </section>
        <section class="det-card">
            <h3>Evaluación de componentes</h3>
            <div class="det-table-wrap">
                <table class="det-table">
                    <thead>
                        <tr>
                            <th>ID detalle</th>
                            <th>Componente</th>
                            <th>Estado</th>
                            <th>Observación</th>
                            <th>Costo estimado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inspeccion->detalles as $detalle)
                            @php
                                $d = $detalle->getAttributes();
                                $estadoDet = strtoupper((string) ($d['estado'] ?? ''));
                            @endphp
                            <tr>
                                <td>#{{ $detalle->getKey() }}</td>
                                <td>{{ $d['componente'] ?? 'No registrado' }}</td>
                                <td><span
                                        class="det-pill {{ $estadoDet === 'BUENO' ? 'det-ok' : ($estadoDet === 'MALO' ? 'det-no' : 'det-wait') }}">{{ $d['estado'] ?? 'N/A' }}</span>
                                </td>
                                <td>{{ $d['observacion'] ?? 'Sin observaciones' }}</td>
                                <td>Q {{ number_format((float) ($d['costo_estimado'] ?? 0), 2) }}</td>
                        </tr>@empty<tr>
                                <td colspan="5" class="det-empty">No hay detalles técnicos registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
        <section class="det-card">
            <h3>Avalúos registrados</h3>
            <div class="det-table-wrap">
                <table class="det-table">
                    <thead>
                        <tr>
                            <th>ID avalúo</th>
                            <th>Valor estimado</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Observaciones</th>
                            <th>Otros datos</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inspeccion->avaluos as $avaluo)@php
                            $a = $avaluo->getAttributes();
                            $ea = strtoupper((string) ($a['estado'] ?? ''));
                        @endphp
                            <tr>
                                <td>#{{ $avaluo->getKey() }}</td>
                                <td><strong>Q {{ number_format((float) ($a['valor_estimado'] ?? 0), 2) }}</strong></td>
                                <td><span
                                        class="det-pill {{ $ea === 'APROBADO' ? 'det-ok' : ($ea === 'RECHAZADO' ? 'det-no' : 'det-wait') }}">{{ $a['estado'] ?? 'N/A' }}</span>
                                </td>
                                <td>{{ $a['fecha_avaluo'] ?? 'No registrada' }}</td>
                                <td>{{ $a['observaciones'] ?? 'Sin observaciones' }}</td>
                                <td class="det-data">
                                    @foreach ($a as $campo => $valor)
                                        @if (
                                            !in_array(strtolower($campo), [
                                                strtolower($avaluo->getKeyName()),
                                                'valor_estimado',
                                                'estado',
                                                'fecha_avaluo',
                                                'observaciones',
                                            ]))
                                            <div><strong>{{ ucfirst(str_replace('_', ' ', $campo)) }}:</strong>
                                                {{ $valor ?? 'N/A' }}</div>
                                        @endif
                                    @endforeach
                                </td>
                        </tr>@empty<tr>
                                <td colspan="6" class="det-empty">Todavía no hay avalúos registrados para esta
                                    inspección.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
        <section class="det-card">
            <div class="det-head">
                <div>
                    <h3 style="margin-bottom:5px">Registrar nuevo avalúo</h3>
                    <p class="det-muted" style="margin:0">Registra la valoración económica del vehículo inspeccionado.</p>
                </div>
            </div>
            <form method="POST" action="{{ route('inspecciones.avaluos.store') }}">@csrf<input type="hidden"
                    name="id_inspeccion" value="{{ $inspeccion->id_inspeccion }}">
                <div class="det-grid">
                    <div class="det-field"><label for="id_usuario_avaluo">ID del inspector</label><input
                            id="id_usuario_avaluo" type="number" name="id_usuario" value="{{ old('id_usuario', 3) }}"
                            min="1" required></div>
                    <div class="det-field"><label for="valor_estimado">Valor estimado (Q)</label><input id="valor_estimado"
                            type="number" name="valor_estimado" value="{{ old('valor_estimado') }}" min="0"
                            step="0.01" required></div>
                    <div class="det-field"><label for="estado_avaluo">Estado del avalúo</label><select id="estado_avaluo"
                            name="estado" required>
                            @foreach (['PENDIENTE', 'APROBADO', 'RECHAZADO'] as $estado)
                                <option value="{{ $estado }}" @selected(old('estado', 'PENDIENTE') === $estado)>{{ $estado }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="det-field" style="margin-top:16px"><label for="observaciones_avaluo">Observaciones del
                        avalúo</label>
                    <textarea id="observaciones_avaluo" name="observaciones" rows="3">{{ old('observaciones') }}</textarea>
                </div>
                @if ($resultado !== 'APROBADA')
                    <div class="det-notice">Esta inspección no está aprobada. El avalúo solo puede registrarse como
                        pendiente o rechazado.</div>
                @endif
                <div style="display:flex;justify-content:flex-end;margin-top:18px"><button class="det-btn"
                        type="submit">Registrar avalúo</button></div>
            </form>
        </section>
    </div>
@endsection
