@extends('layouts.app')
@section('title', 'Inspecciones - COFIHO V2')
@section('page-title', 'Gestión de Inspecciones')
@section('content')
    <style>
        .ins-wrap {
            color: #14233d
        }

        .ins-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
            flex-wrap: wrap
        }

        .ins-title {
            font-size: 22px;
            font-weight: 800;
            margin: 0 0 5px
        }

        .ins-muted {
            font-size: 13px;
            color: #71819a;
            margin: 0
        }

        .ins-btn {
            border: 0;
            border-radius: 9px;
            background: #2563eb;
            color: white;
            padding: 11px 16px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: inline-block
        }

        .ins-btn:hover {
            background: #1d4ed8;
            color: white
        }

        .ins-btn-alt {
            background: #eef3fc;
            color: #23416b
        }

        .ins-btn-alt:hover {
            background: #dce8fa;
            color: #23416b
        }

        .ins-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 20px
        }

        .ins-stat {
            background: #fff;
            border: 1px solid #e4eaf4;
            border-radius: 13px;
            padding: 18px
        }

        .ins-stat small {
            color: #71819a;
            font-size: 12px
        }

        .ins-stat strong {
            display: block;
            font-size: 27px;
            margin-top: 8px
        }

        .ins-panel {
            background: #fff;
            border: 1px solid #e4eaf4;
            border-radius: 14px;
            padding: 22px;
            margin-bottom: 20px;
            box-shadow: 0 8px 26px #14233d08
        }

        .ins-panel h3 {
            font-size: 16px;
            margin: 0 0 6px
        }

        .ins-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 15px
        }

        .ins-field label {
            display: block;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 7px
        }

        .ins-field input,
        .ins-field select,
        .ins-field textarea {
            width: 100%;
            min-height: 41px;
            border: 1px solid #d7e0ee;
            border-radius: 8px;
            background: #fff;
            padding: 9px 11px;
            color: #1e2e48;
            font-size: 13px
        }

        .ins-field textarea {
            resize: vertical
        }

        .ins-section {
            font-size: 13px;
            font-weight: 800;
            margin: 22px 0 12px;
            padding-bottom: 9px;
            border-bottom: 1px solid #e7edf6
        }

        .ins-table-wrap {
            overflow-x: auto
        }

        .ins-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px
        }

        .ins-table th {
            background: #f2f5fa;
            color: #60708a;
            font-size: 10px;
            letter-spacing: .045em;
            text-transform: uppercase;
            text-align: left;
            padding: 13px 11px;
            white-space: nowrap
        }

        .ins-table td {
            padding: 13px 11px;
            border-bottom: 1px solid #e8edf5;
            vertical-align: middle
        }

        .ins-table tr:hover td {
            background: #f9fbff
        }

        .ins-pill {
            display: inline-block;
            border-radius: 99px;
            padding: 5px 9px;
            font-size: 10px;
            font-weight: 800
        }

        .ins-ok {
            background: #dcfce7;
            color: #166534
        }

        .ins-wait {
            background: #fef3c7;
            color: #92400e
        }

        .ins-no {
            background: #fee2e2;
            color: #991b1b
        }

        .ins-filters {
            display: grid;
            grid-template-columns: 2fr 1fr auto;
            gap: 12px;
            align-items: end;
            background: #f7f9fd;
            border: 1px solid #e7edf6;
            border-radius: 10px;
            padding: 14px;
            margin: 17px 0
        }

        .ins-alert {
            padding: 14px 17px;
            border-radius: 10px;
            margin-bottom: 16px;
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca
        }

        .ins-alert-success {
            background: #ecfdf5;
            color: #166534;
            border-color: #bbf7d0
        }

        .ins-hidden {
            display: none
        }

        .ins-component-grid {
            display: grid;
            grid-template-columns: 1.1fr 1fr 1.6fr 1fr;
            gap: 10px;
            align-items: end;
            margin-bottom: 12px;
            padding: 13px;
            border: 1px solid #e7edf6;
            border-radius: 10px
        }

        .ins-component-grid .ins-field {
            min-width: 0
        }

        .ins-empty {
            text-align: center;
            color: #71819a;
            padding: 28px !important
        }

        @media(max-width:1000px) {
            .ins-stats {
                grid-template-columns: repeat(2, 1fr)
            }

            .ins-grid {
                grid-template-columns: repeat(2, 1fr)
            }

            .ins-component-grid {
                grid-template-columns: repeat(2, 1fr)
            }
        }

        @media(max-width:640px) {

            .ins-stats,
            .ins-grid,
            .ins-component-grid,
            .ins-filters {
                grid-template-columns: 1fr
            }

            .ins-panel {
                padding: 15px
            }
        }
    </style>
    @php
        $totalIns = $inspecciones->count();
        $aprobadasIns = $inspecciones->filter(fn($i) => strtoupper((string) $i->resultado) === 'APROBADA')->count();
        $pendientesIns = $inspecciones->filter(fn($i) => strtoupper((string) $i->resultado) === 'PENDIENTE')->count();
        $rechazadasIns = $inspecciones->filter(fn($i) => strtoupper((string) $i->resultado) === 'RECHAZADA')->count();
    @endphp
    @php
        $vehiculosPorId = $vehiculos->keyBy('id_vehiculo');
        $inspectoresPorId = $inspectores->keyBy('id_usuario');
    @endphp
    <div class="ins-wrap">
        @if ($errors->any())
            <div class="ins-alert" role="alert"><strong>No se pudo registrar la inspección:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="ins-head">
            <div>
                <h2 class="ins-title">Inspecciones técnicas</h2>
                <p class="ins-muted">Control y seguimiento de evaluaciones de vehículos usados.</p>
            </div><button type="button" class="ins-btn" id="insOpen" aria-controls="insFormPanel" aria-expanded="false">+
                Nueva inspección</button>
        </div>
        <div class="ins-stats">
            <div class="ins-stat"><small>Total de inspecciones</small><strong>{{ $totalIns }}</strong></div>
            <div class="ins-stat"><small>Aprobadas</small><strong>{{ $aprobadasIns }}</strong></div>
            <div class="ins-stat"><small>Pendientes</small><strong>{{ $pendientesIns }}</strong></div>
            <div class="ins-stat"><small>Rechazadas</small><strong>{{ $rechazadasIns }}</strong></div>
        </div>
        <section id="insFormPanel" class="ins-panel ins-hidden" aria-label="Registrar inspección">
            <div class="ins-head">
                <div>
                    <h3>Nueva inspección técnica</h3>
                    <p class="ins-muted">Completa la evaluación del vehículo usado.</p>
                </div><button type="button" class="ins-btn ins-btn-alt" id="insClose">Cerrar</button>
            </div>
            <form method="POST" action="{{ route('inspecciones.store') }}">@csrf
                <div class="ins-section">01 · Información general</div>
                <div class="ins-grid">
                    <div class="ins-field">
                        <label for="id_vehiculo">Vehículo usado</label>
                        <select id="id_vehiculo" name="id_vehiculo" required>
                            <option value="">Selecciona un vehículo usado</option>
                            @foreach ($vehiculos->filter(fn($v) => in_array(strtoupper((string) $v->estado), ['DISPONIBLE', 'TALLER'])) as $vehiculo)
                                <option value="{{ $vehiculo->id_vehiculo }}" @selected(old('id_vehiculo') == $vehiculo->id_vehiculo)>
                                    {{ $vehiculo->marca }} {{ $vehiculo->modelo }} · {{ $vehiculo->placa ?: 'Sin placa' }} · {{ $vehiculo->vin }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ins-field">
                        <label for="id_usuario">Inspector responsable</label>
                        <select id="id_usuario" name="id_usuario" required>
                            <option value="">Selecciona un inspector</option>
                            @foreach ($inspectores as $inspector)
                                <option value="{{ $inspector->id_usuario }}" @selected(old('id_usuario') == $inspector->id_usuario)>
                                    {{ trim(($inspector->nombres ?? '') . ' ' . ($inspector->apellidos ?? '')) ?: $inspector->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="ins-field">
                        <label>Estado inicial</label>
                        <input type="text" value="PENDIENTE" disabled>
                    </div>
                </div>
                <div class="ins-section">02 · Evaluación técnica</div>
                <p class="ins-muted">Primero se crea la inspección como PENDIENTE. Luego, desde su detalle,
                    se evalúan los ocho componentes y se finaliza como APROBADA o RECHAZADA.</p>
                <div class="ins-section">03 · Observaciones iniciales</div>
                <div class="ins-field"><label for="observaciones">Observaciones de la inspección</label>
                    <textarea id="observaciones" name="observaciones" rows="3"
                        placeholder="Anota los hallazgos y recomendaciones generales">{{ old('observaciones') }}</textarea>
                </div>
                <div style="display:flex;justify-content:flex-end;margin-top:18px"><button type="submit"
                        class="ins-btn">Registrar inspección</button></div>
            </form>
        </section>
        <section class="ins-panel">
            <div class="ins-head" style="margin-bottom:0">
                <div>
                    <h3>Historial de inspecciones</h3>
                    <p class="ins-muted">Consulta inspecciones y sus resultados técnicos.</p>
                </div><span class="ins-muted" id="contadorInspecciones">Mostrando {{ $totalIns }} inspecciones</span>
            </div>
            <div class="ins-filters">
                <div class="ins-field"><label for="buscarInspeccion">Buscar inspección</label><input type="search"
                        id="buscarInspeccion" placeholder="Inspección, placa, marca, modelo o VIN"></div>
                <div class="ins-field"><label for="filtroResultadoInspeccion">Resultado</label><select
                        id="filtroResultadoInspeccion">
                        <option value="">Todos los resultados</option>
                        @foreach (['PENDIENTE', 'APROBADA', 'RECHAZADA'] as $resultado)
                            <option value="{{ $resultado }}">{{ $resultado }}</option>
                        @endforeach
                    </select>
                </div><button type="button" class="ins-btn ins-btn-alt" id="limpiarInspecciones">Limpiar filtros</button>
            </div>
            <div class="ins-table-wrap">
                <table class="ins-table" id="tablaInspecciones">
                    <thead>
                        <tr>
                            <th>Inspección</th>
                            <th>Vehículo</th>
                            <th>VIN</th>
                            <th>Inspector</th>
                            <th>Fecha</th>
                            <th>Resultado</th>
                            <th>Observaciones</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($inspecciones as $inspeccion)
                            <tr class="fila-inspeccion" data-inspeccion="{{ $inspeccion->id_inspeccion }}"
                                data-vehiculo="{{ $inspeccion->id_vehiculo }}"
                                data-vin="{{ $inspeccion->vehiculo->vin ?? '' }}"
                                 data-placa="{{ $vehiculosPorId->get($inspeccion->id_vehiculo)->placa ?? '' }}"
                                 data-marca="{{ $vehiculosPorId->get($inspeccion->id_vehiculo)->marca ?? '' }}"
                                 data-modelo="{{ $vehiculosPorId->get($inspeccion->id_vehiculo)->modelo ?? '' }}"
                                data-resultado="{{ $inspeccion->resultado ?? 'PENDIENTE' }}">
                                <td><strong>#{{ $inspeccion->id_inspeccion }}</strong></td>
                                <td><strong>{{ $vehiculosPorId->get($inspeccion->id_vehiculo)->placa ?: 'Sin placa' }}</strong>
                                    <div class="ins-muted">{{ $vehiculosPorId->get($inspeccion->id_vehiculo)->marca ?? '' }} {{ $vehiculosPorId->get($inspeccion->id_vehiculo)->modelo ?? '' }}</div></td>
                                <td>{{ $inspeccion->vehiculo->vin ?? 'No disponible' }}</td>
                                <td>@php $responsable = $inspectoresPorId->get($inspeccion->id_usuario); @endphp
                                    {{ $responsable ? (trim(($responsable->nombres ?? '') . ' ' . ($responsable->apellidos ?? '')) ?: $responsable->nombre) : ($inspeccion->usuario->nombre ?? 'No disponible') }}</td>
                                <td>{{ $inspeccion->fecha_inspeccion ?? 'No registrada' }}</td>
                                <td>@php $r = strtoupper((string)($inspeccion->resultado ?? 'PENDIENTE')); @endphp<span
                                        class="ins-pill {{ $r === 'APROBADA' ? 'ins-ok' : ($r === 'RECHAZADA' ? 'ins-no' : 'ins-wait') }}">{{ $r }}</span>
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($inspeccion->observaciones ?? 'Sin observaciones', 65) }}
                                </td>
                                <td><a class="ins-btn"
                                        href="{{ route('inspecciones.show', $inspeccion->id_inspeccion) }}">Ver detalle</a>
                                </td>
                        </tr>@empty<tr>
                                <td colspan="8" class="ins-empty">No hay inspecciones registradas.</td>
                            </tr>
                        @endforelse
                        <tr id="sinResultadosInspecciones" style="display:none">
                            <td colspan="8" class="ins-empty">No se encontraron inspecciones con los filtros
                                seleccionados.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const panel = document.getElementById('insFormPanel'),
                open = document.getElementById('insOpen');

            function showForm(visible) {
                panel.classList.toggle('ins-hidden', !visible);
                open.setAttribute('aria-expanded', String(visible));
                if (visible) panel.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
            open.addEventListener('click', () => showForm(panel.classList.contains('ins-hidden')));
            document.getElementById('insClose').addEventListener('click', () => showForm(false));
            @if ($errors->any() || old('id_vehiculo'))
                showForm(true);
            @endif
            const buscar = document.getElementById('buscarInspeccion'),
                resultado = document.getElementById('filtroResultadoInspeccion');

            function filtrar() {
                let n = 0;
                document.querySelectorAll('.fila-inspeccion').forEach(f => {
                    const q = buscar.value.trim().toLowerCase();
                    const match = (!q || [f.dataset.inspeccion, f.dataset.vehiculo, f.dataset.vin, f.dataset.placa, f.dataset.marca, f.dataset.modelo].some(x =>
                        (x || '').toLowerCase().includes(q))) && (!resultado.value || resultado
                        .value === f.dataset.resultado);
                    f.style.display = match ? '' : 'none';
                    if (match) n++
                });
                document.getElementById('contadorInspecciones').textContent = 'Mostrando ' + n + ' inspecciones';
                document.getElementById('sinResultadosInspecciones').style.display = n === 0 && document
                    .querySelectorAll('.fila-inspeccion').length ? '' : 'none';
            }
            buscar.addEventListener('input', filtrar);
            resultado.addEventListener('change', filtrar);
            document.getElementById('limpiarInspecciones').addEventListener('click', () => {
                buscar.value = '';
                resultado.value = '';
                filtrar()
            });
            document.querySelectorAll('.estado-componente').forEach(s => {
                const c = s.closest('.ins-component-grid').querySelector('.costo-componente');

                function update(reset) {
                    if (reset && (s.value === 'REGULAR' || s.value === 'MALO')) c.value = '';
                    if (s.value === 'BUENO' || s.value === 'NO_APLICA') {
                        c.value = '0.00';
                        c.readOnly = true;
                        c.style.background = '#f1f5f9'
                    } else {
                        c.readOnly = false;
                        c.style.background = ''
                    }
                }
                s.addEventListener('change', () => update(true));
                update(false)
            })
        });
    </script>
@endsection
