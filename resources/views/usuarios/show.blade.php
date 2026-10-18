
@extends('layouts.app')

@section('title', 'Detalle de Usuario - COFIHO V2')

@section('page-title', 'Detalle del Usuario')

@section('content')

<div class="card">

    <h2>Información del Usuario</h2>

    <div class="table-responsive">
        <table>
            <tbody>
                <tr>
                    <th>ID Usuario</th>
                    <td>{{ $usuario->id_usuario }}</td>
                </tr>

                <tr>
                    <th>Nombre</th>
                    <td>{{ $usuario->nombre }}</td>
                </tr>

                <tr>
                    <th>Correo Electrónico</th>
                    <td>{{ $usuario->email }}</td>
                </tr>

                <tr>
                    <th>Rol</th>
                    <td>{{ $usuario->rol?->nombre ?? 'Sin rol' }}</td>
                </tr>

                <tr>
                    <th>Estado</th>
                    <td>{{ $usuario->estado ?? 'No definido' }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 24px;">
        <a href="{{ route('usuarios.index') }}" class="btn">
            Volver a Usuarios
        </a>
    </div>

</div>

@endsection
