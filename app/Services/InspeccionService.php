<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use RuntimeException;

class InspeccionService
{
    /**
     * Registra una inspección con sus detalles técnicos.
     */
    public function registrarInspeccion(array $datos): int
    {
        $datos = Validator::make($datos, [
            'id_vehiculo' => 'required|integer',
            'id_usuario' => 'required|integer',
            'resultado' => 'required|in:PENDIENTE,APROBADA,RECHAZADA',
            'observaciones' => 'nullable|string',
            'detalles' => 'required|array|min:1',
            'detalles.*.componente' => 'required|string',
            'detalles.*.estado' => 'required|in:BUENO,REGULAR,MALO,NO_APLICA',
            'detalles.*.observacion' => 'nullable|string',
            'detalles.*.costo_estimado' => 'required|numeric|min:0',
        ])->validate();

        $db = DB::connection('oracle');

        return $db->transaction(function () use ($db, $datos) {

            $vehiculo = $db->selectOne(
                "SELECT ID_VEHICULO, CONDICION
                 FROM VEHICULO
                 WHERE ID_VEHICULO = ?
                 FOR UPDATE NOWAIT",
                [$datos['id_vehiculo']]
            );

            if (!$vehiculo || strtoupper($vehiculo->condicion) !== 'USADO') {
                throw new RuntimeException(
                    'Solo se pueden inspeccionar vehículos usados.'
                );
            }

            $usuario = $db->selectOne(
    "SELECT U.ID_USUARIO
     FROM USUARIO U
     INNER JOIN ROL R
         ON U.ID_ROL = R.ID_ROL
     WHERE U.ID_USUARIO = ?
       AND U.ACTIVO = 1
       AND R.NOMBRE IN ('INSPECTOR', 'ADMINISTRADOR')",
    [$datos['id_usuario']]
);

            if (!$usuario) {
                throw new RuntimeException(
    'El usuario debe estar activo y tener rol INSPECTOR o ADMINISTRADOR.'
);
            }

            $idInspeccion = $this->siguienteId('SQ_INSPECCION', $db);

            $db->insert(
                "INSERT INTO INSPECCION (
                    ID_INSPECCION,
                    ID_VEHICULO,
                    ID_USUARIO,
                    FECHA_INSPECCION,
                    RESULTADO,
                    OBSERVACIONES
                ) VALUES (?, ?, ?, SYSDATE, ?, ?)",
                [
                    $idInspeccion,
                    $datos['id_vehiculo'],
                    $datos['id_usuario'],
                    $datos['resultado'],
                    $datos['observaciones'] ?? null,
                ]
            );

            foreach ($datos['detalles'] as $detalle) {
                $idDetalle = $this->siguienteId(
                    'SQ_DETALLE_INSPECCION',
                    $db
                );

                $db->insert(
                    "INSERT INTO DETALLE_INSPECCION (
                        ID_DETALLE_INS,
                        ID_INSPECCION,
                        COMPONENTE,
                        ESTADO,
                        OBSERVACION,
                        COSTO_ESTIMADO
                    ) VALUES (?, ?, ?, ?, ?, ?)",
                    [
                        $idDetalle,
                        $idInspeccion,
                        $detalle['componente'],
                        $detalle['estado'],
                        $detalle['observacion'] ?? null,
                        $detalle['costo_estimado'],
                    ]
                );
            }

            return $idInspeccion;
        });
    }

    /**
     * Registra un avalúo vinculado a una inspección.
     */
    public function registrarAvaluo(array $datos): int
    {
        $datos = Validator::make($datos, [
            'id_inspeccion' => 'required|integer',
            'id_usuario' => 'required|integer',
            'valor_estimado' => 'required|numeric|min:0',
            'estado' => 'required|in:PENDIENTE,APROBADO,RECHAZADO',
            'observaciones' => 'nullable|string',
        ])->validate();

        $db = DB::connection('oracle');

        return $db->transaction(function () use ($db, $datos) {

            $inspeccion = $db->selectOne(
                "SELECT I.ID_INSPECCION, I.ID_VEHICULO,
                        I.RESULTADO, V.CONDICION
                 FROM INSPECCION I
                 JOIN VEHICULO V
                   ON V.ID_VEHICULO = I.ID_VEHICULO
                 WHERE I.ID_INSPECCION = ?
                 FOR UPDATE OF I.RESULTADO NOWAIT",
                [$datos['id_inspeccion']]
            );

            if (!$inspeccion) {
                throw new RuntimeException('La inspección no existe.');
            }

            if (strtoupper($inspeccion->condicion) !== 'USADO') {
                throw new RuntimeException(
                    'El avalúo requiere un vehículo usado.'
                );
            }

            if (
                $datos['estado'] === 'APROBADO' &&
                strtoupper($inspeccion->resultado) !== 'APROBADA'
            ) {
                throw new RuntimeException(
                    'No se puede aprobar un avalúo sin inspección aprobada.'
                );
            }

           $usuario = $db->selectOne(
    "SELECT U.ID_USUARIO
     FROM USUARIO U
     INNER JOIN ROL R
         ON U.ID_ROL = R.ID_ROL
     WHERE U.ID_USUARIO = ?
       AND U.ACTIVO = 1
       AND R.NOMBRE IN ('INSPECTOR', 'ADMINISTRADOR')",
    [$datos['id_usuario']]
);

            if (!$usuario) {
                throw new RuntimeException(
    'El usuario debe estar activo y tener rol INSPECTOR o ADMINISTRADOR.'
);
            }

            $idAvaluo = $this->siguienteId('SQ_AVALUO', $db);

            $db->insert(
                "INSERT INTO AVALUO (
                    ID_AVALUO,
                    ID_VEHICULO,
                    ID_INSPECCION,
                    ID_USUARIO,
                    FECHA_AVALUO,
                    VALOR_ESTIMADO,
                    ESTADO,
                    OBSERVACIONES
                ) VALUES (?, ?, ?, ?, SYSDATE, ?, ?, ?)",
                [
                    $idAvaluo,
                    $inspeccion->id_vehiculo,
                    $datos['id_inspeccion'],
                    $datos['id_usuario'],
                    $datos['valor_estimado'],
                    $datos['estado'],
                    $datos['observaciones'] ?? null,
                ]
            );

            return $idAvaluo;
        });
    }

    /**
     * Obtiene el siguiente identificador Oracle.
     * Los triggers existentes respetan el ID proporcionado.
     */
    private function siguienteId(string $secuencia, $db): int
    {
        $permitidas = [
            'SQ_INSPECCION',
            'SQ_DETALLE_INSPECCION',
            'SQ_AVALUO',
        ];

        if (!in_array($secuencia, $permitidas, true)) {
            throw new RuntimeException('Secuencia no permitida.');
        }

        $resultado = $db->selectOne(
            "SELECT {$secuencia}.NEXTVAL AS ID FROM DUAL"
        );

        return (int) $resultado->id;
    }
}
