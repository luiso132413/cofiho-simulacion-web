@extends('layouts.app')
@section('title', 'Detalle de usuario - COFIHO V2')
@section('page-title', 'Detalle del Usuario')
@section('content')
    <style>
        .ux-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
            margin-bottom: 24px
        }

        .ux-head h2 {
            margin: 0 0 6px;
            font-size: 21px;
            color: #14243e
        }

        .ux-head p {
            margin: 0;
            color: #64748b;
            font-size: 13px
        }

        .ux-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            text-decoration: none;
            border: 0;
            border-radius: 9px;
            padding: 11px 16px;
            background: #2563eb;
            color: white;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer
        }

        .ux-btn:hover {
            background: #1d4ed8;
            color: #fff
        }

        .ux-btn.secondary {
            background: #e9eef6;
            color: #334155
        }

        .ux-btn.secondary:hover {
            background: #dbe4f0;
            color: #1e293b
        }

        .ux-btn.danger {
            background: #dc2626
        }

        .ux-btn.success {
            background: #16a34a
        }

        .ux-btn:disabled {
            opacity: .45;
            cursor: not-allowed
        }

        .ux-stats {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 20px
        }

        .ux-stat {
            padding: 18px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            border-radius: 12px
        }

        .ux-stat:first-child {
            background: #eaf2ff;
            border-color: #d5e5ff
        }

        .ux-stat strong {
            display: block;
            font-size: 27px;
            color: #152b4a;
            line-height: 1.2
        }

        .ux-stat span {
            display: block;
            color: #64748b;
            font-size: 12px;
            margin-top: 7px
        }

        .ux-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px
        }

        .ux-table th {
            background: #f1f5f9;
            color: #536781;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: .05em;
            padding: 14px 12px;
            white-space: nowrap
        }

        .ux-table td {
            padding: 13px 12px;
            border-bottom: 1px solid #e7edf5;
            vertical-align: middle
        }

        .ux-table tr:hover td {
            background: #f8fbff
        }

        .ux-pill {
            display: inline-flex;
            border-radius: 99px;
            padding: 6px 10px;
            font-size: 11px;
            font-weight: 700;
            background: #eaf1ff;
            color: #2455bf;
            white-space: nowrap
        }

        .ux-pill.ok {
            background: #dcfce7;
            color: #166534
        }

        .ux-pill.off {
            background: #fee2e2;
            color: #991b1b
        }

        .ux-actions {
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            align-items: center
        }

        .ux-actions .ux-btn {
            font-size: 11px;
            padding: 8px 10px
        }

        .ux-actions form {
            margin: 0
        }

        .ux-name {
            font-weight: 700;
            color: #1e293b
        }

        .ux-muted {
            color: #64748b;
            font-size: 12px
        }

        .ux-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px
        }

        .ux-panel {
            border: 1px solid #e2e8f0;
            border-radius: 13px;
            padding: 22px;
            background: #fff
        }

        .ux-panel h3 {
            margin: 0 0 18px;
            font-size: 16px;
            color: #16243a
        }

        .ux-fields {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 17px
        }

        .ux-field {
            display: flex;
            flex-direction: column;
            gap: 7px
        }

        .ux-field label {
            font-weight: 700;
            color: #334155;
            font-size: 12px
        }

        .ux-field input,
        .ux-field select {
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
            padding: 12px;
            border: 1px solid #d4dfec;
            border-radius: 9px;
            background: white;
            color: #172337;
            font-size: 14px
        }

        .ux-field input:focus,
        .ux-field select:focus {
            outline: 0;
            border-color: #2563eb;
            box-shadow: 0 0 0 3px #2563eb20
        }

        .ux-help {
            font-size: 12px;
            color: #64748b;
            margin: 0 0 18px
        }

        .ux-footer {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 22px
        }

        .ux-detail {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 17px
        }

        .ux-detail dt {
            font-size: 12px;
            color: #64748b;
            margin-bottom: 5px
        }

        .ux-detail dd {
            margin: 0;
            color: #172337;
            font-size: 14px;
            font-weight: 600;
            overflow-wrap: anywhere
        }

        .ux-alert {
            padding: 14px 16px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #991b1b;
            border-radius: 10px;
            margin-bottom: 20px
        }

        .ux-alert ul {
            margin: 8px 0 0;
            padding-left: 20px
        }

        @media(max-width:1000px) {
            .ux-stats {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }

            .ux-grid {
                grid-template-columns: 1fr
            }
        }

        @media(max-width:600px) {

            .ux-stats,
            .ux-fields,
            .ux-detail {
                grid-template-columns: 1fr
            }

            .ux-panel {
                padding: 16px
            }
        }
    </style>
    <div class="card">
        <div class="ux-head">
            <div>
                <h2>{{ $usuario->nombre }}</h2>
                <p>Ficha del empleado · Usuario #{{ $usuario->id_usuario }}</p>
            </div><span
                class="ux-pill {{ (int) $usuario->activo === 1 ? 'ok' : 'off' }}">{{ (int) $usuario->activo === 1 ? 'ACTIVO' : 'INACTIVO' }}</span>
        </div>
        <div class="ux-grid">
            <section class="ux-panel">
                <h3>Información personal</h3>
                <dl class="ux-detail">
                    <div>
                        <dt>Nombres</dt>
                        <dd>{{ $usuario->persona?->nombres ?? $usuario->nombre }}</dd>
                    </div>
                    <div>
                        <dt>Apellidos</dt>
                        <dd>{{ $usuario->persona?->apellidos ?? 'No registrados' }}</dd>
                    </div>
                    <div>
                        <dt>DPI</dt>
                        <dd>{{ $usuario->persona?->dpi ?? 'No registrado' }}</dd>
                    </div>
                    <div>
                        <dt>Teléfono</dt>
                        <dd>{{ $usuario->persona?->telefono ?: 'No registrado' }}</dd>
                    </div>
                </dl>
            </section>
            <section class="ux-panel">
                <h3>Información de acceso</h3>
                <dl class="ux-detail">
                    <div>
                        <dt>ID usuario</dt>
                        <dd>{{ $usuario->id_usuario }}</dd>
                    </div>
                    <div>
                        <dt>Correo electrónico</dt>
                        <dd>{{ $usuario->email }}</dd>
                    </div>
                    <div>
                        <dt>Rol</dt>
                        <dd>{{ $usuario->rol?->nombre ?? 'Sin rol asignado' }}</dd>
                    </div>
                    <div>
                        <dt>Fecha de creación</dt>
                        <dd>{{ $usuario->fecha_creacion ? \Carbon\Carbon::parse($usuario->fecha_creacion)->format('d/m/Y') : 'No registrada' }}
                        </dd>
                    </div>
                </dl>
            </section>
        </div>
        <div class="ux-footer"><a class="ux-btn" href="{{ route('usuarios.edit', $usuario->id_usuario) }}">Editar
                usuario</a><a class="ux-btn secondary" href="{{ route('usuarios.index') }}">Volver a usuarios</a></div>
    </div>
@endsection
