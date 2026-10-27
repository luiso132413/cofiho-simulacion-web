@extends('layouts.app')
@section('title', 'Nuevo Cliente - COFIHO V2')
@section('page-title', 'Registrar Cliente')
@section('content')
    <div class="cl-head">
        <div>
            <div class="cl-kicker">Administración comercial</div>
            <h2>Registrar nuevo cliente</h2>
            <p>Ingresa los datos del cliente individual para habilitar reservas y ventas.</p>
        </div>
    </div>
    <div class="cl-panel">
        @if ($errors->any())
            <div class="cl-error"><strong>Corrige los siguientes errores:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form method="POST" action="{{ route('clientes.store') }}">
            @csrf
            <div class="cl-form">
                <div class="cl-field"><label for="nombre">Nombres *</label><input id="nombre" name="nombre" required
                        maxlength="100" value="{{ old('nombre') }}"></div>
                <div class="cl-field"><label for="apellido">Apellidos *</label><input id="apellido" name="apellido"
                        required maxlength="100" value="{{ old('apellido') }}"></div>
                <div class="cl-field"><label for="dpi">DPI *</label><input id="dpi" name="dpi" required
                        maxlength="13" minlength="13" inputmode="numeric" pattern="[0-9]{13}" value="{{ old('dpi') }}"
                        placeholder="13 dígitos"></div>
                <div class="cl-field"><label for="nit">NIT</label><input id="nit" name="nit" maxlength="20"
                        value="{{ old('nit') }}"></div>
                <div class="cl-field"><label for="telefono">Teléfono</label><input id="telefono" name="telefono"
                        maxlength="20" value="{{ old('telefono') }}"></div>
                <div class="cl-field"><label for="email">Correo electrónico</label><input id="email" name="email"
                        type="email" maxlength="150" value="{{ old('email') }}"></div>
                <div class="cl-field wide"><label for="direccion">Dirección</label>
                    <textarea id="direccion" name="direccion" maxlength="255" rows="3">{{ old('direccion') }}</textarea>
                </div>
            </div>
            <div class="cl-buttons">
                <button type="submit" class="cl-btn">Guardar cliente</button>
                <a href="{{ route('clientes.index') }}" class="cl-btn secondary">Cancelar</a>
            </div>
        </form>
    </div>
    <style>
        .cl-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .cl-panel {
            background: #fff;
            border: 1px solid #e4eaf3;
            border-radius: 15px;
            padding: 22px;
            box-shadow: 0 5px 24px #0f172a09
        }

        .cl-form {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px
        }

        .cl-field {
            display: flex;
            flex-direction: column;
            gap: 7px;
            min-width: 0
        }

        .cl-field label {
            font-size: 13px;
            font-weight: 700;
            color: #334155
        }

        .cl-field input,
        .cl-field textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 9px;
            font: inherit;
            color: #0f172a;
            background: #fff
        }

        .cl-field input:focus,
        .cl-field textarea:focus {
            outline: none;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px #2563eb1f
        }

        .wide {
            grid-column: 1/-1
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

        .cl-error {
            padding: 15px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 10px;
            color: #991b1b;
            margin-bottom: 20px
        }

        .cl-error ul {
            margin: 8px 0 0 20px
        }

        @media(max-width:640px) {
            .cl-form {
                grid-template-columns: 1fr
            }

            .wide {
                grid-column: auto
            }

            .cl-panel {
                padding: 16px
            }
        }
    </style>
@endsection
