@extends('layouts.app')
@section('title', 'Detalle del Cliente - COFIHO V2')
@section('page-title', 'Detalle del Cliente')
@section('content')
    <div class="cl-head">
        <div>
            <div class="cl-kicker">Ficha del cliente · #{{ $cliente->id_cliente }}</div>
            <h2>{{ $cliente->nombre }} {{ $cliente->apellido }}</h2>
            <p>Información general y datos de contacto registrados.</p>
        </div>
        <div>
            @if ((int) $cliente->activo === 1)
            <x-estado valor="ACTIVO" />@else<x-estado valor="INACTIVO" />
            @endif
        </div>
    </div>
    <div class="cl-panel">
        <h3>Información del cliente</h3>
        <p class="cl-sub">Datos disponibles en el registro actual.</p>
        <div class="cl-profile">
            <div class="cl-info"><small>Identificador</small><strong>#{{ $cliente->id_cliente }}</strong></div>
            <div class="cl-info"><small>Nombre</small><strong>{{ $cliente->nombre }}</strong></div>
            <div class="cl-info"><small>Apellido</small><strong>{{ $cliente->apellido }}</strong></div>
            <div class="cl-info"><small>DPI</small><strong>{{ $cliente->dpi ?? 'No registrado' }}</strong></div>
            <div class="cl-info"><small>NIT</small><strong>{{ $cliente->nit ?? 'No registrado' }}</strong></div>
            <div class="cl-info"><small>Teléfono</small><strong>{{ $cliente->telefono ?? 'No registrado' }}</strong></div>
            <div class="cl-info"><small>Correo electrónico</small><strong>{{ $cliente->email ?? 'No registrado' }}</strong>
            </div>
            <div class="cl-info" style="grid-column:1/-1">
                <small>Dirección</small><strong>{{ $cliente->direccion ?? 'No registrada' }}</strong></div>
        </div>
        <div class="cl-buttons"><a class="cl-btn" href="{{ route('clientes.edit', $cliente->id_cliente) }}">Editar
                cliente</a><a class="cl-btn secondary" href="{{ route('clientes.index') }}">Volver a clientes</a></div>
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
