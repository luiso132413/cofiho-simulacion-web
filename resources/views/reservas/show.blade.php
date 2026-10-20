
@extends('layouts.app')

@section('title', 'Detalle de Reserva - COFIHO V2')

@section('page-title', 'Detalle de Reserva')

@section('content')

@php
    $estado = strtoupper(trim($reserva->estado ?? ''));

    $fechaExpiracion = $reserva->fecha_expiracion
        ? \Carbon\Carbon::parse($reserva->fecha_expiracion)
        : null;

    $puedeRegistrarDevolucion = in_array($estado, ['EXPIRADA', 'CANCELADA'])
        && ($saldoDevolucion ?? 0) > 0;

    $puedeRegistrarEnganche =
        in_array($estado, ['PENDIENTE', 'CONFIRMADA'])
        && $fechaExpiracion
        && $fechaExpiracion->isFuture();
@endphp

<div class="card">

    <h2>Información de la Reserva</h2>

    @if (session('success'))
    <div style="background:#dcfce7; color:#166534; padding:15px; border-radius:8px; margin-bottom:20px;">
        {{ session('success') }}
    </div>
@endif

   @if ($errors->any())
    <div style="background:#fee2e2; color:#991b1b; padding:15px; border-radius:8px; margin-bottom:20px;">
        <strong>No se pudo completar la operación:</strong>
        <ul style="margin:10px 0 0 20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

    <div class="table-responsive">
        <table>
            <tbody>
                <tr>
                    <th>ID Reserva</th>
                    <td>{{ $reserva->id_reserva }}</td>
                </tr>

                <tr>
                    <th>ID Cliente</th>
                    <td>{{ $reserva->id_cliente }}</td>
                </tr>

                <tr>
                    <th>ID Vehículo</th>
                    <td>{{ $reserva->id_vehiculo }}</td>
                </tr>

                <tr>
                    <th>Fecha de Reserva</th>
                    <td>{{ $reserva->fecha_reserva ?? 'No registrada' }}</td>
                </tr>

                <tr>
                    <th>Fecha de Expiración</th>
                    <td>{{ $reserva->fecha_expiracion ?? 'No registrada' }}</td>
                </tr>

                <tr>
                    <th>Estado</th>
                    <td>{{ $reserva->estado ?? 'No definido' }}</td>
                </tr>

                <tr>
                    <th>Canal</th>
                    <td>{{ $reserva->canal ?? 'No registrado' }}</td>
                </tr>

                <tr>
                    <th>ID Usuario</th>
                    <td>{{ $reserva->id_usuario ?? 'No asignado' }}</td>
                </tr>

                <tr>
                    <th>Enganche Registrado</th>
                    <td>
                        Q {{ number_format((float) ($reserva->monto_enganche ?? 0), 2) }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="margin-top: 24px;">
        <a href="{{ route('reservas.index') }}" class="btn">
            Volver a Reservas
        </a>
    </div>

</div>

@if ($puedeRegistrarEnganche)

    <div class="card" style="margin-top: 24px;">

        <h2>Registrar Enganche</h2>

        <p>
            Registra únicamente pagos que ya hayan sido confirmados.
        </p>

        <form
            method="POST"
            action="{{ route('reservas.enganche', $reserva->id_reserva) }}"
        >
            @csrf

            <div style="margin-bottom: 16px;">
                <label for="monto">Monto del Enganche (Q)</label>

                <input
                    type="number"
                    id="monto"
                    name="monto"
                    min="0.01"
                    step="0.01"
                    value="{{ old('monto') }}"
                    required
                    style="display: block; width: 100%; padding: 10px;"
                >
            </div>

            <div style="margin-bottom: 16px;">
                <label for="id_metodo_pago">Método de Pago</label>

                <select
                    id="id_metodo_pago"
                    name="id_metodo_pago"
                    required
                    style="display: block; width: 100%; padding: 10px;"
                >
                    <option value="">Seleccione un método</option>

                    <option value="1" @selected(old('id_metodo_pago') == '1')>
                        VisaNet
                    </option>

                    <option value="2" @selected(old('id_metodo_pago') == '2')>
                        Transferencia BAC
                    </option>

                    <option value="3" @selected(old('id_metodo_pago') == '3')>
                        POS Agencia
                    </option>
                </select>
            </div>

            <div style="margin-bottom: 16px;">
                <label for="referencia">Referencia del Pago</label>

                <input
                    type="text"
                    id="referencia"
                    name="referencia"
                    maxlength="100"
                    value="{{ old('referencia') }}"
                    placeholder="Ejemplo: BAC-123456"
                    required
                    style="display: block; width: 100%; padding: 10px;"
                >
            </div>

            <button type="submit" class="btn">
                Registrar Enganche
            </button>

        </form>

    </div>

@else

    <div class="card" style="margin-top: 24px;">
        <h2>Registro de Enganche</h2>

        <p>
            No se pueden registrar enganches porque la reserva
            no está vigente.
        </p>
    </div>

@endif


@if (in_array($estado, ['EXPIRADA', 'CANCELADA']))
    <div class="card" style="margin-top: 24px;">
        <h2>Devoluciones de Enganche</h2>
        <p>Saldo disponible para devolución: <strong>Q {{ number_format((float) ($saldoDevolucion ?? 0), 2) }}</strong></p>

        @if ($puedeRegistrarDevolucion)
            <p><strong>Atención:</strong> registra la devolución únicamente cuando el dinero ya haya sido entregado y confirmado. Esta operación se guarda como APROBADA.</p>

            <form method="POST" action="{{ route('reservas.devolucion', $reserva->id_reserva) }}"
                  onsubmit="return confirm('¿Confirmas que el dinero ya fue devuelto realmente? Esta operación quedará registrada como APROBADA.');">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label for="monto_devolucion">Monto a devolver (Q)</label>
                    <input type="number" id="monto_devolucion" name="monto" min="0.01" step="0.01"
                           max="{{ number_format((float) $saldoDevolucion, 2, '.', '') }}"
                           value="{{ old('monto') }}" required
                           style="display: block; width: 100%; padding: 10px;">
                </div>
                <div style="margin-bottom: 16px;">
                    <label for="metodo_devolucion">Método de devolución</label>
                    <select id="metodo_devolucion" name="id_metodo_pago" required
                            style="display: block; width: 100%; padding: 10px;">
                        <option value="">Seleccione un método</option>
                        <option value="1" @selected(old('id_metodo_pago') == '1')>VisaNet</option>
                        <option value="2" @selected(old('id_metodo_pago') == '2')>Transferencia BAC</option>
                        <option value="3" @selected(old('id_metodo_pago') == '3')>POS Agencia</option>
                    </select>
                </div>
                <div style="margin-bottom: 16px;">
                    <label for="referencia_devolucion">Referencia de la devolución confirmada</label>
                    <input type="text" id="referencia_devolucion" name="referencia" maxlength="120"
                           value="{{ old('referencia') }}" required
                           style="display: block; width: 100%; padding: 10px;">
                </div>
                <button type="submit" class="btn">Registrar Devolución Confirmada</button>
            </form>
        @else
            <p>No hay saldo pendiente para devolver.</p>
        @endif
    </div>
@endif

@endsection
