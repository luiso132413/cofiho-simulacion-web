@extends('layouts.app')
@section('title', 'Registrar vehículo - COFIHO V2')
@section('page-title', 'Registrar vehículo')
@push('styles')
    <style>
        .reg-wrap {
            max-width: 1050px;
            margin: auto
        }

        .reg-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 20px
        }

        .reg-title {
            font-size: 23px;
            font-weight: 800;
            color: #14233e;
            margin: 0
        }

        .reg-muted {
            color: #64748b;
            font-size: 13px
        }

        .reg-card {
            background: #fff;
            border: 1px solid #e4eaf3;
            border-radius: 15px;
            padding: 24px;
            margin-bottom: 18px;
            box-shadow: 0 4px 18px rgba(15, 23, 42, .03)
        }

        .reg-card h3 {
            font-size: 15px;
            font-weight: 800;
            color: #14233e;
            margin: 0 0 20px
        }

        .reg-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px
        }

        .reg-field {
            display: flex;
            flex-direction: column;
            gap: 7px
        }

        .reg-field label {
            font-size: 12px;
            font-weight: 750;
            color: #334155
        }

        .reg-field input,
        .reg-field select {
            width: 100%;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            padding: 11px 12px;
            font-size: 13px;
            background: #fff;
            color: #14233e
        }

        .reg-field input:focus,
        .reg-field select:focus {
            outline: 2px solid #bfdbfe;
            border-color: #2563eb
        }

        .reg-help {
            font-size: 11px;
            color: #64748b
        }

        .reg-error {
            font-size: 12px;
            color: #b91c1c
        }

        .reg-alert {
            border-radius: 10px;
            padding: 13px 15px;
            background: #fff7ed;
            color: #9a3412;
            font-size: 12px;
            margin-top: 17px
        }

        .reg-actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-bottom: 30px
        }

        .reg-btn {
            border: 0;
            border-radius: 10px;
            padding: 12px 20px;
            background: #2563eb;
            color: #fff;
            font-weight: 800;
            font-size: 13px;
            cursor: pointer
        }

        .reg-cancel {
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #334155;
            text-decoration: none;
            border-radius: 10px;
            padding: 12px 20px;
            font-size: 13px;
            font-weight: 700
        }

        @media(max-width:650px) {
            .reg-grid {
                grid-template-columns: 1fr
            }

            .reg-card {
                padding: 16px
            }

            .reg-head {
                align-items: flex-start;
                flex-direction: column
            }
        }
    </style>
@endpush
@section('content')
    <div class="reg-wrap">
        <div class="reg-head">
            <div>
                <h2 class="reg-title">Nuevo vehículo</h2>
                <p class="reg-muted">Completa los datos de identificación, características y ubicación inicial.</p>
            </div>
        </div>
        @if ($errors->any())
            <div class="reg-alert" style="margin-bottom:18px"><strong>Revisa el formulario:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('vehiculos.store') }}" method="POST" enctype="multipart/form-data" id="formVehiculo">@csrf
            <section class="reg-card">
                <h3>01 · Identificación</h3>
                <div class="reg-grid">
                    <div class="reg-field">
                        <label for="vin">VIN interno</label>
                        <input id="vin" type="text" value="Se generará automáticamente al guardar" readonly
                            style="background:#f1f5f9;color:#64748b;cursor:not-allowed">
                        <span class="reg-help">Formato COFIHO + consecutivo Oracle (17 caracteres). No requiere ingreso manual.</span>
                    </div>
                    <div class="reg-field"><label for="motor">Número de motor *</label><input id="motor"
                            name="motor" value="{{ old('motor') }}" maxlength="60" required></div>
                    <div class="reg-field"><label for="placa">Placa (opcional)</label><input id="placa"
                            name="placa" value="{{ old('placa') }}" maxlength="20" style="text-transform:uppercase">
                    </div>
                </div>
            </section>
            <section class="reg-card">
                <h3>02 · Características comerciales</h3>
                <div class="reg-grid">
                    <div class="reg-field"><label for="id_marca">Marca *</label><select id="id_marca" name="id_marca"
                            required>
                            <option value="">Selecciona una marca</option>
                            @foreach ($marcas as $marca)
                                <option value="{{ $marca->id_marca }}" @selected(old('id_marca') == $marca->id_marca)>{{ $marca->nombre }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="reg-field"><label for="id_modelo">Modelo *</label><select id="id_modelo" name="id_modelo"
                            required>
                            <option value="">Selecciona primero una marca</option>
                            @foreach ($modelos as $modelo)
                                <option value="{{ $modelo->id_modelo }}" data-marca="{{ $modelo->id_marca }}"
                                    @selected(old('id_modelo') == $modelo->id_modelo)>{{ $modelo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="reg-field"><label for="id_tipo_vehiculo">Tipo de vehículo *</label><select
                            id="id_tipo_vehiculo" name="id_tipo_vehiculo" required>
                            <option value="">Selecciona un tipo</option>
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo->id_tipo_vehiculo }}" @selected(old('id_tipo_vehiculo') == $tipo->id_tipo_vehiculo)>
                                    {{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="reg-field"><label for="anio">Año *</label><input id="anio" type="number"
                            name="anio" min="1900" max="2100" value="{{ old('anio', date('Y')) }}" required>
                    </div>
                    <div class="reg-field"><label for="color">Color *</label><input id="color" name="color"
                            maxlength="60" value="{{ old('color') }}" required></div>
                    <div class="reg-field"><label for="condicion">Condición *</label><select id="condicion" name="condicion"
                            required>
                            <option value="NUEVO" @selected(old('condicion', 'NUEVO') === 'NUEVO')>Nuevo</option>
                            <option value="USADO" @selected(old('condicion') === 'USADO')>Usado</option>
                        </select></div>
                    <div class="reg-field"><label for="precio_lista">Precio de lista (Q) *</label><input id="precio_lista"
                            type="number" step="0.01" min="0" name="precio_lista"
                            value="{{ old('precio_lista') }}" required></div>
                </div>
                <div class="reg-alert" id="avisoUsado" hidden>Los vehículos usados se registran en estado
                    <strong>TALLER</strong>. Permanecerán fuera del catálogo comercial hasta completar inspección, avalúo y
                    habilitación administrativa.</div>
            </section>
            <section class="reg-card">
                <h3>03 · Ubicación inicial</h3>
                <div class="reg-grid">
                    <div class="reg-field"><label for="id_predio">Predio *</label><select id="id_predio" name="id_predio"
                            required>
                            <option value="">Selecciona un predio activo</option>
                            @foreach ($predios as $predio)
                                <option value="{{ $predio->id_predio }}" @selected(old('id_predio') == $predio->id_predio)>
                                    {{ $predio->nombre }}</option>
                            @endforeach
                        </select></div>
                    <div class="reg-field"><label for="ubicacion">Ubicación dentro del predio (opcional)</label><input
                            id="ubicacion" name="ubicacion" maxlength="100" value="{{ old('ubicacion') }}"
                            placeholder="Ej. Fila A, espacio 04"></div>
                </div>
            </section>
            <section class="reg-card">
                <h3>04 · Fotografía para catálogo público</h3>
                <div class="reg-field">
                    <label for="foto_vehiculo">Fotografía del vehículo (opcional)</label>
                    <input id="foto_vehiculo" name="foto_vehiculo" type="file"
                        accept="image/jpeg,image/png,image/webp">
                    <span class="reg-help">Una fotografía por vehículo. JPG, PNG o WebP; máximo 3 MB. Se guardará en Laravel Storage al registrar el vehículo.</span>
                    @error('foto_vehiculo')<span class="reg-error">{{ $message }}</span>@enderror
                </div>
            </section>
            <div class="reg-actions"><a class="reg-cancel" href="{{ route('vehiculos.index') }}">Cancelar</a><button
                    class="reg-btn" type="submit">Guardar vehículo</button></div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const marca = document.getElementById('id_marca'),
                modelo = document.getElementById('id_modelo'),
                condicion = document.getElementById('condicion'),
                aviso = document.getElementById('avisoUsado');

            function filtrar() {
                const elegido = marca.value;
                Array.from(modelo.options).forEach(o => {
                    if (!o.value) return;
                    o.hidden = o.dataset.marca !== elegido;
                    o.disabled = o.dataset.marca !== elegido;
                });
                if (modelo.selectedOptions[0]?.disabled) modelo.value = '';
            }

            function actualizar() {
                aviso.hidden = condicion.value !== 'USADO';
            }
            marca.addEventListener('change', filtrar);
            condicion.addEventListener('change', actualizar);
            filtrar();
            actualizar();
        });
    </script>
@endsection
