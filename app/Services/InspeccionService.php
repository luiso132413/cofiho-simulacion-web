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
            'observaciones' => 'nullable|string',
        ])->validate();

        $db = DB::connection('oracle');
        return $db->transaction(function () use ($db, $datos) {
            $vehiculo = $db->selectOne(
                "SELECT ID_VEHICULO, CONDICION, ESTADO FROM VEHICULO
                 WHERE ID_VEHICULO = ? FOR UPDATE NOWAIT",
                [$datos['id_vehiculo']]
            );
            if (!$vehiculo || strtoupper($vehiculo->condicion) !== 'USADO') {
                throw new RuntimeException('Solo se pueden inspeccionar vehículos usados.');
            }
            if (in_array(strtoupper($vehiculo->estado), ['VENDIDO', 'INACTIVO', 'RESERVADO'], true)) {
                throw new RuntimeException('Este vehículo no admite una nueva inspección en su estado actual.');
            }
            $this->validarResponsable($db, (int) $datos['id_usuario']);
            $abierta = $db->selectOne(
                "SELECT COUNT(*) AS TOTAL FROM INSPECCION
                 WHERE ID_VEHICULO = ? AND RESULTADO = 'PENDIENTE'",
                [$datos['id_vehiculo']]
            );
            if ((int) $abierta->total > 0) {
                throw new RuntimeException('El vehículo ya tiene una inspección pendiente. Finalízala antes de crear otra.');
            }
            $id = $this->siguienteId('SQ_INSPECCION', $db);
            $db->insert(
                "INSERT INTO INSPECCION
                 (ID_INSPECCION, ID_VEHICULO, ID_USUARIO, FECHA_INSPECCION, RESULTADO, OBSERVACIONES)
                 VALUES (?, ?, ?, SYSDATE, 'PENDIENTE', ?)",
                [$id, $datos['id_vehiculo'], $datos['id_usuario'], $datos['observaciones'] ?? null]
            );
            return $id;
        });
    }

    public function finalizarInspeccion(int $id, array $datos): void
    {
        $componentes = ['Motor', 'Frenos', 'Carrocería', 'Transmisión',
            'Suspensión', 'Sistema eléctrico', 'Neumáticos', 'Interior'];
        $datos = Validator::make($datos, [
            'resultado' => 'required|in:APROBADA,RECHAZADA',
            'observaciones' => 'required_if:resultado,RECHAZADA|nullable|string',
            'detalles' => 'required|array|size:8',
            'detalles.*.estado' => 'required|in:BUENO,REGULAR,MALO,NO_APLICA',
            'detalles.*.observacion' => 'nullable|string',
            'detalles.*.costo_estimado' => 'required|numeric|min:0',
        ])->validate();
        $db = DB::connection('oracle');
        $db->transaction(function () use ($db, $id, $datos, $componentes) {
            $ins = $db->selectOne(
                'SELECT ID_INSPECCION, ID_VEHICULO, RESULTADO FROM INSPECCION
                 WHERE ID_INSPECCION = ? FOR UPDATE NOWAIT', [$id]
            );
            if (!$ins || strtoupper($ins->resultado) !== 'PENDIENTE') {
                throw new RuntimeException('Solo se pueden finalizar inspecciones pendientes.');
            }
            $ultima = $db->selectOne(
                'SELECT ID_INSPECCION FROM (
                    SELECT ID_INSPECCION FROM INSPECCION WHERE ID_VEHICULO = ?
                    ORDER BY FECHA_INSPECCION DESC, ID_INSPECCION DESC
                 ) WHERE ROWNUM = 1', [$ins->id_vehiculo]
            );
            if ((int) $ultima->id_inspeccion !== $id) {
                throw new RuntimeException('Existe una inspección posterior. No se puede finalizar esta inspección histórica.');
            }
            $existentes = $db->selectOne(
                'SELECT COUNT(*) AS TOTAL FROM DETALLE_INSPECCION WHERE ID_INSPECCION = ?', [$id]
            );
            if ((int) $existentes->total > 0) {
                throw new RuntimeException('La inspección ya contiene componentes. Se requiere revisión antes de finalizarla.');
            }
            foreach ($componentes as $i => $componente) {
                $d = $datos['detalles'][$i] ?? null;
                if (!$d) {
                    throw new RuntimeException('Falta evaluar el componente '.$componente.'.');
                }
                $estado = $d['estado'];
                $costo = (float) $d['costo_estimado'];
                if (in_array($estado, ['BUENO', 'NO_APLICA'], true) && $costo > 0) {
                    throw new RuntimeException('El costo debe ser cero para componentes BUENO o NO_APLICA.');
                }
                $db->insert(
                    'INSERT INTO DETALLE_INSPECCION
                     (ID_DETALLE_INS, ID_INSPECCION, COMPONENTE, ESTADO, OBSERVACION, COSTO_ESTIMADO)
                     VALUES (?, ?, ?, ?, ?, ?)',
                    [$this->siguienteId('SQ_DETALLE_INSPECCION', $db), $id,
                     $componente, $estado, $d['observacion'] ?? null, $costo]
                );
            }
            $db->update(
                'UPDATE INSPECCION SET RESULTADO = ?, OBSERVACIONES = ? WHERE ID_INSPECCION = ?',
                [$datos['resultado'], $datos['observaciones'] ?? null, $id]
            );
        });
    }

    private function validarResponsable($db, int $id): void
    {
        $usuario = $db->selectOne(
            "SELECT U.ID_USUARIO FROM USUARIO U JOIN ROL R ON R.ID_ROL = U.ID_ROL
             WHERE U.ID_USUARIO = ? AND U.ACTIVO = 1
               AND R.NOMBRE IN ('INSPECTOR', 'ADMINISTRADOR')", [$id]
        );
        if (!$usuario) {
            throw new RuntimeException('El responsable debe estar activo y ser INSPECTOR o ADMINISTRADOR.');
        }
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

            if (strtoupper($inspeccion->resultado) !== 'APROBADA') {
                throw new RuntimeException('Solo se puede registrar un avalúo para una inspección APROBADA.');
            }
            $ultima = $db->selectOne(
                'SELECT ID_INSPECCION FROM (
                    SELECT ID_INSPECCION FROM INSPECCION WHERE ID_VEHICULO = ?
                    ORDER BY FECHA_INSPECCION DESC, ID_INSPECCION DESC
                 ) WHERE ROWNUM = 1', [$inspeccion->id_vehiculo]
            );
            if ((int) $ultima->id_inspeccion !== (int) $datos['id_inspeccion']) {
                throw new RuntimeException('Solo se puede avaluar la inspección más reciente.');
            }
            $existente = $db->selectOne(
                'SELECT COUNT(*) AS TOTAL FROM AVALUO WHERE ID_INSPECCION = ?',
                [$datos['id_inspeccion']]
            );
            if ((int) $existente->total > 0) {
                throw new RuntimeException('Esta inspección ya tiene un avalúo registrado.');
            }
            $this->validarResponsable($db, (int) $datos['id_usuario']);

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
