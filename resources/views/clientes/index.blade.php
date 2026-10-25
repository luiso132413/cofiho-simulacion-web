
@extends('layouts.app')

@section('title', 'Clientes - COFIHO V2')
@section('page-title', 'Gestión de Clientes')

@section('content')

<div class="card">

    <h2>Listado de Clientes</h2>

    <p>
        Consulta y administración de los clientes
        registrados en COFIHO V2.
    </p>

    <div class="table-responsive">

        <table
            data-filtrable
            data-filtros="Tipo de cliente"
        >
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Tipo de cliente</th>
                    <th>DPI</th>
                    <th>NIT</th>
                    <th>Teléfono</th>
                    <th>Correo Electrónico</th>
                    <th>Dirección</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->id_cliente }}</td>

                        <td>{{ $cliente->nombre }}</td>

                        <td>{{ $cliente->apellido }}</td>

                        <td>
                            {{ $cliente->tipo_cliente ?? 'No definido' }}
                        </td>

                        <td>{{ $cliente->dpi ?? 'No registrado' }}</td>

                        <td>{{ $cliente->nit ?? 'No registrado' }}</td>

                        <td>{{ $cliente->telefono ?? 'No registrado' }}</td>

                        <td>{{ $cliente->email ?? 'No registrado' }}</td>

                        <td>{{ $cliente->direccion ?? 'No registrada' }}</td>

                        
<td>
    @if((int) $cliente->activo === 1)
        <x-estado valor="ACTIVO" />
    @else
        <x-estado valor="INACTIVO" />
    @endif
</td>
    
<td>
    <div style="display: flex; gap: 8px; align-items: center;">

        <a
            href="{{ route('clientes.show', $cliente->id_cliente) }}"
            class="btn"
        >
            Ver
        </a>

        <a
            href="{{ route('clientes.edit', $cliente->id_cliente) }}"
            class="btn"
            style="background: #d97706;"
        >
            Editar
        </a>

        <form
            action="{{ route('clientes.estado', $cliente->id_cliente) }}"
            method="POST"
            onsubmit="return confirm(@js((int) $cliente->activo === 1
                ? '¿Deseas desactivar este cliente? Su historial se conservará.'
                : '¿Deseas activar este cliente?'));"
            style="margin: 0;"
        >
            @csrf
            @method('PATCH')

            @if((int) $cliente->activo === 1)
                <button
                    type="submit"
                    class="btn"
                    style="background: #dc2626;"
                >
                    Desactivar
                </button>
            @else
                <button
                    type="submit"
                    class="btn"
                    style="background: #16a34a;"
                >
                    Activar
                </button>
            @endif
        </form>

    </div>
</td>


                    </tr>
                @empty
                    <tr>
                        <td colspan="11" style="text-align:center;">
                            No hay clientes registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>

</div>

@endsection
