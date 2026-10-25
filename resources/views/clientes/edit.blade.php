
@extends('layouts.app')

@section('title', 'Editar Cliente - COFIHO V2')
@section('page-title', 'Editar Cliente')

@section('content')

<div class="card">

    <h2>Editar información del cliente</h2>

    <p>
        Actualiza los datos del cliente.
        Su identificador y su historial de operaciones
        se conservarán.
    </p>

    @if ($errors->any())
        <div class="alert alert-danger"
             style="padding: 12px; margin-bottom: 20px;
                    background: #fee2e2; color: #991b1b;
                    border-radius: 8px;">
            <strong>Corrige los siguientes errores:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('clientes.update', $cliente->id_cliente) }}"
        method="POST"
    >
        @csrf
        @method('PUT')

        
<div class="formulario-clientes" style="
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 20px;
">


            <div>
                <label for="nombre">Nombre *</label>
                <input
                    type="text"
                    id="nombre"
                    name="nombre"
                    class="form-control"
                    value="{{ old('nombre', $cliente->nombre) }}"
                    maxlength="100"
                    required
                >
            </div>

            <div>
                <label for="apellido">Apellido *</label>
                <input
                    type="text"
                    id="apellido"
                    name="apellido"
                    class="form-control"
                    value="{{ old('apellido', $cliente->apellido) }}"
                    maxlength="100"
                    required
                >
            </div>

            <div>
                <label for="tipo_cliente">Tipo de cliente *</label>
                <select
                    id="tipo_cliente"
                    name="tipo_cliente"
                    class="form-control"
                    required
                >
                    <option value="INDIVIDUAL"
                        @selected(old('tipo_cliente', $cliente->tipo_cliente) === 'INDIVIDUAL')>
                        Individual
                    </option>

                    <option value="EMPRESARIAL"
                        @selected(old('tipo_cliente', $cliente->tipo_cliente) === 'EMPRESARIAL')>
                        Empresarial
                    </option>
                </select>
            </div>

            <div>
                <label for="dpi">DPI</label>
                <input
                    type="text"
                    id="dpi"
                    name="dpi"
                    class="form-control"
                    value="{{ old('dpi', $cliente->dpi) }}"
                    maxlength="20"
                >
            </div>

            <div>
                <label for="nit">NIT</label>
                <input
                    type="text"
                    id="nit"
                    name="nit"
                    class="form-control"
                    value="{{ old('nit', $cliente->nit) }}"
                    maxlength="20"
                >
            </div>

            <div>
                <label for="telefono">Teléfono</label>
                <input
                    type="text"
                    id="telefono"
                    name="telefono"
                    class="form-control"
                    value="{{ old('telefono', $cliente->telefono) }}"
                    maxlength="20"
                >
            </div>

            <div>
                <label for="email">Correo electrónico</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email', $cliente->email) }}"
                    maxlength="150"
                >
            </div>

            <div style="grid-column: 1 / -1;">
                <label for="direccion">Dirección</label>
                <textarea
                    id="direccion"
                    name="direccion"
                    class="form-control"
                    rows="3"
                    maxlength="255"
                    style="width: 100%;"
                >{{ old('direccion', $cliente->direccion) }}</textarea>
            </div>

        </div>

        <div style="display: flex;
                    gap: 12px;
                    margin-top: 24px;
                    flex-wrap: wrap;">

            <button type="submit" class="btn">
                Guardar cambios
            </button>

            <a
                href="{{ route('clientes.show', $cliente->id_cliente) }}"
                class="btn"
                style="background: #64748b;"
            >
                Cancelar
            </a>

        </div>

    </form>

</div>


<style>
    .formulario-clientes > div {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 0;
    }

    .formulario-clientes label {
        font-weight: 600;
        font-size: 13px;
        color: #334155;
    }

    .formulario-clientes input,
    .formulario-clientes select,
    .formulario-clientes textarea {
        width: 100%;
        box-sizing: border-box;
        padding: 11px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 7px;
        background: #ffffff;
        color: #0f172a;
        font-size: 14px;
    }

    .formulario-clientes input:focus,
    .formulario-clientes select:focus,
    .formulario-clientes textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
    }
</style>


@endsection
