
@props(['valor' => ''])

@php
    $estado = strtoupper(trim((string) $valor));

    $clase = match ($estado) {
        'DISPONIBLE', 'ACTIVO', 'ACTIVA',
        'APROBADO', 'APROBADA',
        'CONFIRMADA', 'COMPLETADA',
        'CONCILIADA' => 'badge-ok',

        'PENDIENTE', 'RESERVADO',
        'RESERVADA', 'REGULAR',
        'EN PROCESO' => 'badge-warn',

        'VENDIDO', 'CANCELADA',
        'CANCELADO', 'EXPIRADA',
        'RECHAZADA', 'RECHAZADO',
        'INACTIVO', 'INACTIVA' => 'badge-bad',

        default => 'badge-neutral',
    };
@endphp

<span class="badge {{ $clase }}">
    {{ $estado !== '' ? $estado : 'SIN ESTADO' }}
</span>
