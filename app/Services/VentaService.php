
<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use RuntimeException;

class VentaService
{
    public function procesarVenta(
        int $idCliente,
        int $idVehiculo,
        ?int $idUsuario,
        int $idMetodoPago,
        ?int $idReserva = null
    ): int {
        $conexion = DB::connection('oracle');

        return $conexion->transaction(function () use (
            $conexion,
            $idCliente,
            $idVehiculo,
            $idUsuario,
            $idMetodoPago,
            $idReserva
        ) {
            $pdo = $conexion->getPdo();

            $sql = "BEGIN
                SP_PROCESAR_VENTA(
                    :cliente,
                    :vehiculo,
                    :usuario,
                    :metodo_pago,
                    :reserva,
                    :venta
                );
            END;";

            $stmt = $pdo->prepare($sql);

            $idVenta = 0;

            $stmt->bindValue(
                ':cliente',
                $idCliente,
                \PDO::PARAM_INT
            );

            $stmt->bindValue(
                ':vehiculo',
                $idVehiculo,
                \PDO::PARAM_INT
            );

            $stmt->bindValue(
                ':usuario',
                $idUsuario,
                $idUsuario === null
                    ? \PDO::PARAM_NULL
                    : \PDO::PARAM_INT
            );

            $stmt->bindValue(
                ':metodo_pago',
                $idMetodoPago,
                \PDO::PARAM_INT
            );

            $stmt->bindValue(
                ':reserva',
                $idReserva,
                $idReserva === null
                    ? \PDO::PARAM_NULL
                    : \PDO::PARAM_INT
            );

            $stmt->bindParam(
                ':venta',
                $idVenta,
                \PDO::PARAM_INT | \PDO::PARAM_INPUT_OUTPUT,
                40
            );

            $stmt->execute();

            if ((int) $idVenta <= 0) {
                throw new RuntimeException(
                    'Oracle no devolvió un ID de venta válido.'
                );
            }

            return (int) $idVenta;
        });
    }
}
