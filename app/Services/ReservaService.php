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
    string $canal
): array {
    $conexion = DB::connection('oracle');

    return $conexion->transaction(function () use (
        $conexion,
        $idPredio,
        $idCliente,
        $idUsuario,
        $canal
    ) {
        $pdo = $conexion->getPdo();

        $sql = "BEGIN
            SP_RESERVAR_FIFO(
                :predio,
                :cliente,
                :usuario,
                :canal,
                :vehiculo,
                :reserva
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

}
