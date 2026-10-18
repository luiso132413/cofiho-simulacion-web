
@extends('layouts.app')

@section('title', 'Detalle del Cliente - COFIHO V2')

@section('page-title', 'Detalle del Cliente')

@section('content')

<div class="card">

    <h2>Información del Cliente</h2>

    <table>
        <tbody>
            <tr>
                <th>ID Cliente</th>
                <td>{{ $cliente->id_cliente }}</td>
            </tr>

            <tr>
                <th>Nombre</th>
                <td>{{ $cliente->nombre }}</td>
            </tr>

            <tr>
                <th>Apellido</th>
                <td>{{ $cliente->apellido }}</td>
            </tr>

            <tr>
                <th>DPI</th>
                <td>{{ $cliente->dpi ?? 'No registrado' }}</td>
            </tr>

            <tr>
                <th>NIT</th>
                <td>{{ $cliente->nit ?? 'No registrado' }}</td>
            </tr>

            <tr>
                <th>Teléfono</th>
                <td>{{ $cliente->telefono ?? 'No registrado' }}</td>
            </tr>

            <tr>
                <th>Correo Electrónico</th>
                <td>{{ $cliente->email ?? 'No registrado' }}</td>
            </tr>

            <tr>
                <th>Dirección</th>
                <td>{{ $cliente->direccion ?? 'No registrada' }}</td>
            </tr>
        </tbody>
    </table>

    <div style="margin-top: 24px;">
        <a href="{{ route('clientes.index') }}" class="btn">
            Volver a Clientes
        </a>
    </div>

</div>

@endsection
