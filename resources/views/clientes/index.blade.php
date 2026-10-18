
@extends('layouts.app')

@section('title', 'Clientes - COFIHO V2')

@section('page-title', 'Gestión de Clientes')

@section('content')

<div class="card">

    <h2>Listado de Clientes</h2>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>DPI</th>
                    <th>NIT</th>
                    <th>Teléfono</th>
                    <th>Correo Electrónico</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse($clientes as $cliente)
                    <tr>
                        <td>{{ $cliente->id_cliente }}</td>

                        <td>{{ $cliente->nombre }}</td>

                        <td>{{ $cliente->apellido }}</td>

                        <td>{{ $cliente->dpi ?? 'No registrado' }}</td>

                        <td>{{ $cliente->nit ?? 'No registrado' }}</td>

                        <td>{{ $cliente->telefono ?? 'No registrado' }}</td>

                        <td>{{ $cliente->email ?? 'No registrado' }}</td>

                        <td>
                            <a href="{{ route('clientes.show', $cliente->id_cliente) }}"
                               class="btn">
                                Ver detalles
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" style="text-align:center;">
                            No hay clientes registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
