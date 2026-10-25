@extends('layouts.app')

@section('title', 'Registrar Usuario - COFIHO V2')

@section('page-title', 'Registrar Nuevo Usuario')

@section('content')

<div class="card">

    <h2>Datos del nuevo empleado</h2>

    <p>Complete la información para crear una cuenta de acceso a COFIHO.</p>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>No se pudo registrar el usuario:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf

        <h3>Información personal</h3>

        <div class="form-group">
            <label for="nombres">Nombres *</label>
            <input
                type="text"
                id="nombres"
                name="nombres"
                value="{{ old('nombres') }}"
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
                value="{{ old('apellidos') }}"
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
                value="{{ old('dpi') }}"
                maxlength="13"
                minlength="13"
                pattern="[0-9]{13}"
                inputmode="numeric"
                placeholder="13 dígitos numéricos"
                required
            >
        </div>

        <div class="form-group">
            <label for="telefono">Teléfono</label>
            <input
                type="text"
                id="telefono"
                name="telefono"
                value="{{ old('telefono') }}"
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
                value="{{ old('email') }}"
                maxlength="150"
                required
            >
        </div>

        <div class="form-group">
            <label for="id_rol">Rol del empleado *</label>
            <select id="id_rol" name="id_rol" required>
                <option value="">Seleccione un rol</option>

                @foreach ($roles as $rol)
                    <option
                        value="{{ $rol->id_rol }}"
                        {{ old('id_rol') == $rol->id_rol ? 'selected' : '' }}
                    >
                        {{ $rol->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="password">Contraseña *</label>
            <input
                type="password"
                id="password"
                name="password"
                minlength="8"
                autocomplete="new-password"
                required
            >
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirmar contraseña *</label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                minlength="8"
                autocomplete="new-password"
                required
            >
        </div>

        <hr>

        <div style="display:flex; gap:10px; flex-wrap:wrap; margin-top:20px;">

            <button type="submit" class="btn">
                Registrar Usuario
            </button>

            <a href="{{ route('usuarios.index') }}" class="btn">
                Cancelar
            </a>

        </div>

    </form>

</div>

@endsection