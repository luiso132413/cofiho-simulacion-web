<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use RuntimeException;

class ReservaService
{
    public function crearReserva(
        int $idCliente,
        int $idVehiculo,
        ?int $idUsuario,
        string $canal
    ): int {
        $conexion = DB::connection('oracle');

        return $conexion->transaction(function () use (
            $conexion,
            $idCliente,
            $idVehiculo,
            $idUsuario,
            $canal
        ) {
            $pdo = $conexion->getPdo();

            $sql = "BEGIN
                SP_CREAR_RESERVA(
                    :cliente,
                    :vehiculo,
                    :usuario,
                    :canal,
                    :reserva
                );
            END;";

            $stmt = $pdo->prepare($sql);

            $idReserva = 0;

            $stmt->bindValue(':cliente', $idCliente, \PDO::PARAM_INT);
            $stmt->bindValue(':vehiculo', $idVehiculo, \PDO::PARAM_INT);

            if ($idUsuario === null) {
                $stmt->bindValue(':usuario', null, \PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':usuario', $idUsuario, \PDO::PARAM_INT);
            }

            $stmt->bindValue(':canal', $canal, \PDO::PARAM_STR);

            $stmt->bindParam(
                ':reserva',
                $idReserva,
                \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT,
                40
            );

            $stmt->execute();

            if ((int) $idReserva <= 0) {
                throw new RuntimeException(
                    'Oracle no devolvió un ID de reserva válido.'
                );
            }

            return (int) $idReserva;
        });
    }

    
public function reservarFIFO(
    int $idPredio,
    int $idCliente,
    ?int $idUsuario,
    string $canal,
    int $idModelo,
    int $anio,
    string $color
): array {
    $conexion = DB::connection('oracle');

    return $conexion->transaction(function () use (
        $conexion,
        $idPredio,
        $idCliente,
        $idUsuario,
        $canal,
        $idModelo,
        $anio,
        $color
    ) {
        $pdo = $conexion->getPdo();

        $sql = "BEGIN
            SP_RESERVAR_FIFO(
                :predio,
                :cliente,
                :usuario,
                :canal,
                :vehiculo,
                :reserva,
                :modelo,
                :anio,
                :color
            );
        END;";

        $stmt = $pdo->prepare($sql);

        $idVehiculo = 0;
        $idReserva = 0;

        $stmt->bindValue(
            ':predio',
            $idPredio,
            \PDO::PARAM_INT
        );

        $stmt->bindValue(
            ':cliente',
            $idCliente,
            \PDO::PARAM_INT
        );

        if ($idUsuario === null) {
            $stmt->bindValue(
                ':usuario',
                null,
                \PDO::PARAM_NULL
            );
        } else {
            $stmt->bindValue(
                ':usuario',
                $idUsuario,
                \PDO::PARAM_INT
            );
        }

        $stmt->bindValue(
            ':canal',
            $canal,
            \PDO::PARAM_STR
        );

        $stmt->bindParam(
            ':vehiculo',
            $idVehiculo,
            \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT,
            40
        );

        $stmt->bindParam(
            ':reserva',
            $idReserva,
            \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT,
            40
        );

        $stmt->bindValue(':modelo', $idModelo, \PDO::PARAM_INT);
        $stmt->bindValue(':anio', $anio, \PDO::PARAM_INT);
        $stmt->bindValue(':color', trim($color), \PDO::PARAM_STR);

        $stmt->execute();

        if ($idVehiculo <= 0 || $idReserva <= 0) {
            throw new \RuntimeException(
                'Oracle no devolvió identificadores válidos.'
            );
        }

        return [
            'id_vehiculo' => (int) $idVehiculo,
            'id_reserva' => (int) $idReserva,
        ];
    });
}

public function registrarEnganche(
    int $idReserva,
    int $idMetodoPago,
    float $monto,
    string $referencia
): void {
    $conexion = DB::connection('oracle');

    $conexion->transaction(function () use (
        $conexion,
        $idReserva,
        $idMetodoPago,
        $monto,
        $referencia
    ) {
        $pdo = $conexion->getPdo();

        $sql = "BEGIN
            SP_REGISTRAR_ENGANCHE(
                P_ID_RESERVA     => :reserva,
                P_ID_METODO_PAGO => :metodo,
                P_MONTO          => :monto,
                P_REFERENCIA     => :referencia
            );
        END;";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':reserva', $idReserva, \PDO::PARAM_INT);
        $stmt->bindValue(':metodo', $idMetodoPago, \PDO::PARAM_INT);
        $stmt->bindValue(':monto', $monto, \PDO::PARAM_STR);
        $stmt->bindValue(':referencia', $referencia, \PDO::PARAM_STR);

        $stmt->execute();
    });
}


    public function registrarDevolucion(
        int $idReserva,
        int $idMetodoPago,
        float $monto,
        string $referencia
    ): void {
        $conexion = DB::connection('oracle');

        $conexion->transaction(function () use (
            $conexion,
            $idReserva,
            $idMetodoPago,
            $monto,
            $referencia
        ) {
            $pdo = $conexion->getPdo();

            $sql = "BEGIN
                SP_REGISTRAR_DEVOLUCION(
                    P_ID_RESERVA     => :reserva,
                    P_ID_METODO_PAGO => :metodo,
                    P_MONTO          => :monto,
                    P_REFERENCIA     => :referencia
                );
            END;";

            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':reserva', $idReserva, \PDO::PARAM_INT);
            $stmt->bindValue(':metodo', $idMetodoPago, \PDO::PARAM_INT);
            $stmt->bindValue(':monto', $monto, \PDO::PARAM_STR);
            $stmt->bindValue(':referencia', trim($referencia), \PDO::PARAM_STR);
            $stmt->execute();
        });
    }

    
public function checkoutReserva(
    int $idCliente,
    int $idVehiculo,
    ?int $idUsuario,
    string $canal,
    int $idMetodoPago,
    float $montoEnganche,
    string $referencia
): array {
    $conexion = DB::connection('oracle');

    return $conexion->transaction(function () use (
        $conexion,
        $idCliente,
        $idVehiculo,
        $idUsuario,
        $canal,
        $idMetodoPago,
        $montoEnganche,
        $referencia
    ) {
        $pdo = $conexion->getPdo();

        $sql = "BEGIN
            SP_CHECKOUT_RESERVA(
                P_ID_CLIENTE     => :cliente,
                P_ID_VEHICULO    => :vehiculo,
                P_ID_USUARIO     => :usuario,
                P_CANAL          => :canal,
                P_ID_METODO_PAGO => :metodo,
                P_MONTO_ENGANCHE => :monto,
                P_REFERENCIA     => :referencia,
                P_ID_RESERVA     => :reserva,
                P_GASTOS         => :gastos
            );
        END;";

        $stmt = $pdo->prepare($sql);

        $stmt->bindValue(':cliente', $idCliente, \PDO::PARAM_INT);
        $stmt->bindValue(':vehiculo', $idVehiculo, \PDO::PARAM_INT);

        if ($idUsuario === null) {
            $stmt->bindValue(':usuario', null, \PDO::PARAM_NULL);
        } else {
            $stmt->bindValue(':usuario', $idUsuario, \PDO::PARAM_INT);
        }

        $stmt->bindValue(':canal', $canal, \PDO::PARAM_STR);
        $stmt->bindValue(':metodo', $idMetodoPago, \PDO::PARAM_INT);
        $stmt->bindValue(':monto', $montoEnganche, \PDO::PARAM_STR);
        $stmt->bindValue(':referencia', trim($referencia), \PDO::PARAM_STR);

        $idReserva = 0;
        $gastos = 0;

        $stmt->bindParam(
            ':reserva',
            $idReserva,
            \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT,
            40
        );

        $stmt->bindParam(
            ':gastos',
            $gastos,
            \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT,
            40
        );

        $stmt->execute();

        if ((int) $idReserva <= 0) {
            throw new RuntimeException(
                'Oracle no devolvió un ID de reserva válido.'
            );
        }

        return [
            'id_reserva' => (int) $idReserva,
            'gastos' => (float) $gastos,
        ];
    });
}


}
