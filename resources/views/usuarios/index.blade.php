
@extends('layouts.app')

@section('title', 'Usuarios - COFIHO V2')

@section('page-title', 'Gestión de Usuarios')

@section('content')

<div class="card">

    <h2>Listado de Usuarios</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo Electrónico</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id_usuario }}</td>

                        <td>{{ $usuario->nombre }}</td>

                        <td>{{ $usuario->email }}</td>

                        <td>
                            {{ $usuario->rol?->nombre ?? 'Sin rol' }}
                        </td>

                        <td>
                            {{ $usuario->estado ?? 'No definido' }}
                        </td>

                        <td>
                            <a href="{{ route('usuarios.show', $usuario->id_usuario) }}"
                               class="btn">
                                Ver detalles
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center;">
                            No hay usuarios registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
