
@extends('layouts.app')

@section('title', 'Usuarios - COFIHO V2')
@section('page-title', 'Gestión de Usuarios')

@push('styles')
<style>
    .usuarios-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .usuarios-header h2 {
        margin: 0;
    }

    .usuarios-header p {
        margin: 6px 0 0;
        font-size: 13px;
        color: #64748b;
    }

    .usuarios-tabla {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    .usuarios-tabla th {
        white-space: nowrap;
    }

    .usuarios-tabla td {
        vertical-align: middle;
    }

    .usuario-nombre {
        font-weight: 600;
        color: #172337;
    }

    .usuario-email {
        overflow-wrap: anywhere;
    }

    .usuario-rol {
        display: inline-block;
        padding: 5px 9px;
        background: #eff6ff;
        color: #1d4ed8;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
    }

    .usuario-estado {
        display: inline-block;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .usuario-estado.activo {
        background: #dcfce7;
        color: #166534;
    }

    .usuario-estado.inactivo {
        background: #fee2e2;
        color: #991b1b;
    }

    .usuario-acciones {
        display: flex;
        align-items: center;
        gap: 6px;
        flex-wrap: wrap;
        min-width: 240px;
    }

    .usuario-acciones form {
        margin: 0;
    }

    .usuario-acciones .btn {
        font-size: 12px;
        padding: 8px 11px;
        white-space: nowrap;
    }

    .btn-secundario {
        background: #e2e8f0;
        color: #334155;
    }

    .btn-secundario:hover {
        background: #cbd5e1;
    }

    .btn-desactivar {
        background: #dc2626;
    }

    .btn-desactivar:hover {
        background: #b91c1c;
    }

    .btn-activar {
        background: #16a34a;
    }

    .btn-activar:hover {
        background: #15803d;
    }

    .usuario-acciones .btn:disabled {
        background: #cbd5e1;
        color: #64748b;
        cursor: not-allowed;
        opacity: 0.65;
    }
</style>
@endpush

@section('content')

<div class="card">

    <div class="usuarios-header">

        <div>
            <h2>Listado de Usuarios</h2>
            <p>
                Administración de empleados y cuentas de acceso
                al sistema COFIHO.
            </p>
        </div>

        <a href="{{ route('usuarios.create') }}" class="btn">
            + Registrar Usuario
        </a>

    </div>

    <div class="table-responsive">

        <table class="usuarios-tabla">

            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo electrónico</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>

                @forelse ($usuarios as $usuario)

                    <tr>

                        <td>
                            {{ $usuario->id_usuario }}
                        </td>

                        <td>
                            <span class="usuario-nombre">
                                {{ $usuario->nombre }}
                            </span>
                        </td>

                        <td class="usuario-email">
                            {{ $usuario->email }}
                        </td>

                        <td>
                            <span class="usuario-rol">
                                {{ $usuario->rol?->nombre ?? 'Sin rol' }}
                            </span>
                        </td>

                        <td>
                            @if ((int) $usuario->activo === 1)
                                <span class="usuario-estado activo">
                                    ACTIVO
                                </span>
                            @else
                                <span class="usuario-estado inactivo">
                                    INACTIVO
                                </span>
                            @endif
                        </td>

                        <td>

                            <div class="usuario-acciones">

                                <a
                                    href="{{ route('usuarios.show', $usuario->id_usuario) }}"
                                    class="btn btn-secundario"
                                >
                                    Detalles
                                </a>

                                <a
                                    href="{{ route('usuarios.edit', $usuario->id_usuario) }}"
                                    class="btn"
                                >
                                    Editar
                                </a>

                                <form
                                    action="{{ route('usuarios.estado', $usuario->id_usuario) }}"
                                    method="POST"
                                >

                                    @csrf
                                    @method('PATCH')

                                    @php
                                        $esUsuarioActual =
                                            (int) $usuario->id_usuario ===
                                            (int) auth()->id();

                                        $estaActivo =
                                            (int) $usuario->activo === 1;
                                    @endphp

                                    <button
                                        type="submit"
                                        class="btn {{ $estaActivo ? 'btn-desactivar' : 'btn-activar' }}"
                                        @if ($esUsuarioActual)
                                            disabled
                                            title="No puedes desactivar tu propia cuenta"
                                        @else
                                            onclick="return confirm('¿Confirmas el cambio de estado de este usuario?')"
                                        @endif
                                    >
                                        {{ $estaActivo ? 'Desactivar' : 'Activar' }}
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" style="text-align: center; padding: 30px;">
                            No hay usuarios registrados.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection
