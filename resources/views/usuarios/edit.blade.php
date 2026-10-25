
@extends('layouts.app')

@section('title', 'Editar Usuario - COFIHO V2')

@section('page-title', 'Editar Usuario')

@section('content')

<div class="card">

    <h2>Editar información del usuario</h2>

    <p>
        Usuario #{{ $usuario->id_usuario }} —
        {{ $usuario->nombre }}
    </p>

    @if ($errors->any())
        <div class="alert alert-danger" style="margin-bottom: 20px;">
            <strong>No se pudieron guardar los cambios:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('usuarios.update', $usuario->id_usuario) }}"
          method="POST">

        @csrf
        @method('PUT')

        <h3>Información personal</h3>

        <div class="form-group">
            <label for="nombres">Nombres *</label>
            <input
                type="text"
                id="nombres"
                name="nombres"
                value="{{ old('nombres', $usuario->persona?->nombres ?? $usuario->nombre) }}"
                maxlength="100"
                required
            >
        </div>

        <div class="form-group">
            <label for="apellidos">Apellidos *</label>
            <input
                type="text"
                id="apellidos"
                name="apellidos"
                value="{{ old('apellidos', $usuario->persona?->apellidos ?? '') }}"
                maxlength="100"
                required
            >
        </div>

        <div class="form-group">
            <label for="dpi">DPI *</label>
            <input
                type="text"
                id="dpi"
                name="dpi"
                value="{{ old('dpi', $usuario->persona?->dpi ?? '') }}"
                minlength="13"
                maxlength="13"
                pattern="[0-9]{13}"
                inputmode="numeric"
                required
            >
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input
                type="text"
                id="telefono"
                name="telefono"
                value="{{ old('telefono', $usuario->persona?->telefono ?? '') }}"
                maxlength="25"
            >
        </div>

        <hr>

        <h3>Información de acceso</h3>

        <div class="form-group">
            <label for="email">Correo electrónico *</label>
            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email', $usuario->email) }}"
                maxlength="150"
                required
            >
        </div>

        <div class="form-group">
            <label for="id_rol">Rol *</label>
            <select id="id_rol" name="id_rol" required>
                <option value="">Seleccione un rol</option>

                @foreach ($roles as $rol)
                    <option
                        value="{{ $rol->id_rol }}"
                        {{ old('id_rol', $usuario->id_rol) == $rol->id_rol ? 'selected' : '' }}
                    >
                        {{ $rol->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <hr>

        <h3>Cambiar contraseña (opcional)</h3>

        <p>
            Deja estos campos vacíos si deseas conservar
            la contraseña actual.
        </p>

        <div class="form-group">
            <label for="password">Nueva contraseña</label>
            <input
                type="password"
                id="password"
                name="password"
                minlength="8"
                autocomplete="new-password"
            >
        </div>

        <div class="form-group">
            <label for="password_confirmation">
                Confirmar nueva contraseña
            </label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                minlength="8"
                autocomplete="new-password"
            >
        </div>

        <div style="
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 20px;
        ">

            <button type="submit" class="btn">
                Guardar Cambios
            </button>

            <a href="{{ route('usuarios.index') }}" class="btn">
                Cancelar
            </a>

        </div>

    </form>

</div>

@endsection
