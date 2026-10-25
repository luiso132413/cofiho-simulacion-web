
@extends('layouts.app')

@section('title', 'Detalle de Usuario - COFIHO V2')
@section('page-title', 'Detalle del Usuario')

@push('styles')
<style>
    .detalle-usuario-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .detalle-usuario-header h2 {
        margin: 0;
    }

    .detalle-usuario-header p {
        margin: 6px 0 0;
        font-size: 13px;
        color: #64748b;
    }

    .detalle-usuario-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .detalle-seccion {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 20px;
    }

    .detalle-seccion h3 {
        margin: 0 0 18px;
        font-size: 16px;
        color: #172337;
    }

    .detalle-campo {
        margin-bottom: 16px;
    }

    .detalle-campo:last-child {
        margin-bottom: 0;
    }

    .detalle-etiqueta {
        display: block;
        color: #64748b;
        font-size: 12px;
        margin-bottom: 5px;
    }

    .detalle-valor {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        overflow-wrap: anywhere;
    }

    .detalle-estado {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
    }

    .detalle-estado.activo {
        background: #dcfce7;
        color: #166534;
    }

    .detalle-estado.inactivo {
        background: #fee2e2;
        color: #991b1b;
    }

    .detalle-acciones {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-top: 24px;
    }

    .detalle-btn-volver {
        background: #e2e8f0;
        color: #334155;
    }

    .detalle-btn-volver:hover {
        background: #cbd5e1;
    }

    @media (max-width: 768px) {
        .detalle-usuario-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')

<div class="card">

    <div class="detalle-usuario-header">

        <div>
            <h2>Información del Usuario</h2>
            <p>
                Consulta de datos personales y acceso al sistema.
            </p>
        </div>

        @if ((int) $usuario->activo === 1)
            <span class="detalle-estado activo">
                ACTIVO
            </span>
        @else
            <span class="detalle-estado inactivo">
                INACTIVO
            </span>
        @endif

    </div>

    <div class="detalle-usuario-grid">

        <div class="detalle-seccion">

            <h3>Información personal</h3>

            <div class="detalle-campo">
                <span class="detalle-etiqueta">Nombres</span>
                <span class="detalle-valor">
                    {{ $usuario->persona?->nombres ?? $usuario->nombre }}
                </span>
            </div>

            <div class="detalle-campo">
                <span class="detalle-etiqueta">Apellidos</span>
                <span class="detalle-valor">
                    {{ $usuario->persona?->apellidos ?? 'No registrados' }}
                </span>
            </div>

            <div class="detalle-campo">
                <span class="detalle-etiqueta">DPI</span>
                <span class="detalle-valor">
                    {{ $usuario->persona?->dpi ?? 'No registrado' }}
                </span>
            </div>

            <div class="detalle-campo">
                <span class="detalle-etiqueta">Teléfono</span>
                <span class="detalle-valor">
                    {{ $usuario->persona?->telefono ?: 'No registrado' }}
                </span>
            </div>

        </div>

        <div class="detalle-seccion">

            <h3>Información de acceso</h3>

            <div class="detalle-campo">
                <span class="detalle-etiqueta">ID de usuario</span>
                <span class="detalle-valor">
                    {{ $usuario->id_usuario }}
                </span>
            </div>

            <div class="detalle-campo">
                <span class="detalle-etiqueta">Nombre de usuario</span>
                <span class="detalle-valor">
                    {{ $usuario->nombre }}
                </span>
            </div>

            <div class="detalle-campo">
                <span class="detalle-etiqueta">Correo electrónico</span>
                <span class="detalle-valor">
                    {{ $usuario->email }}
                </span>
            </div>

            <div class="detalle-campo">
                <span class="detalle-etiqueta">Rol</span>
                <span class="detalle-valor">
                    {{ $usuario->rol?->nombre ?? 'Sin rol asignado' }}
                </span>
            </div>

            <div class="detalle-campo">
                <span class="detalle-etiqueta">Fecha de creación</span>
                <span class="detalle-valor">
                    {{ $usuario->fecha_creacion
                        ? \Carbon\Carbon::parse($usuario->fecha_creacion)->format('d/m/Y')
                        : 'No registrada' }}
                </span>
            </div>

        </div>

    </div>

    <div class="detalle-acciones">

        <a
            href="{{ route('usuarios.edit', $usuario->id_usuario) }}"
            class="btn"
        >
            Editar Usuario
        </a>

        <a
            href="{{ route('usuarios.index') }}"
            class="btn detalle-btn-volver"
        >
            Volver a Usuarios
        </a>

    </div>

</div>

@endsection
