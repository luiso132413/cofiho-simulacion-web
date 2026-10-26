@extends('layouts.app')
@section('title', 'Editar Cliente - COFIHO V2')
@section('page-title', 'Editar Cliente')
@section('content')
    <div class="cl-head">
        <div>
            <div class="cl-kicker">Administración de clientes · #{{ $cliente->id_cliente }}</div>
            <h2>Editar información del cliente</h2>
            <p>Actualiza sus datos sin modificar el identificador ni el historial de operaciones.</p>
        </div>
    </div>
    <div class="cl-panel">
        @if ($errors->any())
            <div
                style="padding:15px;background:#fef2f2;border:1px solid #fecaca;border-radius:10px;color:#991b1b;margin-bottom:20px">
                <strong>Corrige los siguientes errores:</strong>
                <ul style="margin:8px 0 0 20px">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('clientes.update', $cliente->id_cliente) }}" method="POST">@csrf @method('PUT')
            <div class="cl-form">
                <div class="cl-field"><label for="nombre">Nombre *</label><input id="nombre" name="nombre"
                        type="text" maxlength="100" required value="{{ old('nombre', $cliente->nombre) }}"></div>
                <div class="cl-field"><label for="apellido">Apellido *</label><input id="apellido" name="apellido"
                        type="text" maxlength="100" required value="{{ old('apellido', $cliente->apellido) }}"></div>
                <div class="cl-field"><label for="tipo_cliente">Tipo de cliente *</label><select id="tipo_cliente"
                        name="tipo_cliente" required>
                        <option value="INDIVIDUAL" @selected(old('tipo_cliente', $cliente->tipo_cliente) === 'INDIVIDUAL')>Individual</option>
                        <option value="EMPRESARIAL" @selected(old('tipo_cliente', $cliente->tipo_cliente) === 'EMPRESARIAL')>Empresarial</option>
                    </select></div>
                <div class="cl-field"><label for="dpi">DPI</label><input id="dpi" nam   e="dpi" maxlength="20"
                        value="{{ old('dpi', $cliente->dpi) }}"></div>
                <div class="cl-field"><label for="nit">NIT</label><input id="nit" name="nit" maxlength="20"
                        value="{{ old('nit', $cliente->nit) }}"></div>
                <div class="cl-field"><label for="telefono">Teléfono</label><input id="telefono" name="telefono"
                        maxlength="20" value="{{ old('telefono', $cliente->telefono) }}"></div>
                <div class="cl-field"><label for="email">Correo electrónico</label><input id="email" name="email"
                        type="email" maxlength="150" value="{{ old('email', $cliente->email) }}"></div>
                <div class="cl-field wide"><label for="direccion">Dirección</label>
                    <textarea id="direccion" name="direccion" rows="3" maxlength="255">{{ old('direccion', $cliente->direccion) }}</textarea>
                </div>
            </div>
            <div class="cl-buttons"><button class="cl-btn" type="submit">Guardar cambios</button><a
                    class="cl-btn secondary" href="{{ route('clientes.show', $cliente->id_cliente) }}">Cancelar</a></div>
        </form>
    </div>

    <style>
        .cl-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 22px
        }

        .cl-kicker {
            font-size: 11px;
            letter-spacing: .12em;
            font-weight: 800;
            color: #2563eb;
            text-transform: uppercase
        }

        .cl-head h2 {
            font-size: 23px;
            margin: 7px 0 5px;
            color: #132440
        }

        .cl-head p {
            margin: 0;
            color: #64748b
        }

        .cl-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
            margin-bottom: 22px
        }

        .cl-stat {
            background: #fff;
            border: 1px solid #e4eaf3;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 3px 15px #0f172a08
        }

        .cl-stat span {
            font-size: 12px;
            color: #64748b
        }

        .cl-stat strong {
            display: block;
            font-size: 29px;
            color: #152a49;
            margin-top: 9px
        }

        .cl-stat:first-child {
            background: #eaf2ff;
            border-color: #d6e5ff
        }

        .cl-panel {
            background: #fff;
            border: 1px solid #e4eaf3;
            border-radius: 15px;
            padding: 22px;
            box-shadow: 0 5px 24px #0f172a09
        }

        .cl-panel h3 {
            font-size: 17px;
            color: #142643;
            margin-bottom: 6px
        }

        .cl-panel .cl-sub {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 18px
        }

        .cl-table {
            min-width: 1100px
        }

        .cl-table th {
            font-size: 11px;
            letter-spacing: .055em;
            text-transform: uppercase;
            white-space: nowrap
        }

        .cl-table td {
            font-size: 13px;
            vertical-align: middle
        }

        .cl-name {
            font-weight: 750;
            color: #152a49
        }

        .cl-muted {
            font-size: 12px;
            color: #64748b
        }

        .cl-actions {
            display: flex;
            gap: 6px;
            flex-wrap: nowrap;
            align-items: center
        }

        .cl-actions form {
            margin: 0
        }

        .cl-action {
            border: 0;
            border-radius: 8px;
            padding: 9px 11px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
            display: inline-flex;
            align-items: center
        }

        .cl-action.view {
            background: #e8f0ff;
            color: #1d4ed8
        }

        .cl-action.edit {
            background: #fff2dc;
            color: #9a5700
        }

        .cl-action.disable {
            background: #fee2e2;
            color: #b91c1c
        }

        .cl-action.enable {
            background: #dcfce7;
            color: #166534
        }

        .cl-action:hover {
            filter: brightness(.95)
        }

        .cl-profile {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 15px
        }

        .cl-info {
            padding: 17px;
            background: #f8fafc;
            border: 1px solid #e7edf5;
            border-radius: 11px
        }

        .cl-info small {
            display: block;
            color: #64748b;
            font-size: 12px;
            margin-bottom: 7px
        }

        .cl-info strong {
            font-size: 14px;
            color: #172b49;
            overflow-wrap: anywhere
        }

        .cl-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 22px
        }

        .cl-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 9px;
            background: #2563eb;
            color: white;
            text-decoration: none;
            padding: 12px 17px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer
        }

        .cl-btn.secondary {
            background: #e8edf5;
            color: #334155
        }

        .cl-form {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px
        }

        .cl-form .cl-field {
            display: flex;
            flex-direction: column;
            gap: 7px;
            min-width: 0
        }

        .cl-form label {
            font-size: 13px;
            font-weight: 700;
            color: #334155
        }

        .cl-form input,
        .cl-form select,
        .cl-form textarea {
            width: 100%;
            max-width: none;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            font: inherit;
            background: white;
            color: #0f172a
        }

        .cl-form input:focus,
        .cl-form select:focus,
        .cl-form textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px #2563eb1f
        }

        .cl-form .wide {
            grid-column: 1/-1
        }

        @media(max-width:1050px) {
            .cl-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media(max-width:640px) {

            .cl-stats,
            .cl-profile,
            .cl-form {
                grid-template-columns: 1fr
            }

            .cl-panel {
                padding: 16px
            }
        }
    </style>
@endsection
