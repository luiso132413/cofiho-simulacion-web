<?php

namespace App\Http\Controllers;

use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class VehiculoController extends Controller
{
    /**
     * RF - Gestión Predial
     *
     * Muestra los vehículos con su predio actual
     * y calcula el stock disponible por predio.
     */
    public function index()
    {
        // Consultar vehículos y sus relaciones.
        $vehiculos = Vehiculo::with([
            'modelo.marca',
            'tipoVehiculo',
            'inventarios.predio',
        ])
        ->orderBy('id_vehiculo', 'asc')
        ->get();

        // Identificar el inventario vigente.
        foreach ($vehiculos as $vehiculo) {

            $inventarioActual = $vehiculo->inventarios
                ->filter(function ($inventario) {
                    return is_null($inventario->fecha_salida);
                })
                ->sortByDesc(function ($inventario) {
                    return sprintf(
                        '%s-%020d',
                        $inventario->fecha_ingreso ?? '',
                        (int) $inventario->id_inventario
                    );
                })
                ->first();

            // No utilizar inventarios históricos
            // como si fueran ubicaciones actuales.
            $vehiculo->setRelation(
                'inventarioActual',
                $inventarioActual
            );
        }

        // Calcular stock disponible por predio.
        $stockPorPredio = $vehiculos
            ->filter(function ($vehiculo) {

                return strtoupper(
                    trim((string) $vehiculo->estado)
                ) === 'DISPONIBLE'
                && $vehiculo->inventarioActual !== null
                && $vehiculo->inventarioActual->predio !== null;

            })
            ->groupBy(function ($vehiculo) {

                return $vehiculo
                    ->inventarioActual
                    ->id_predio;

            })
            ->map(function ($grupo) {

                $predio = $grupo
                    ->first()
                    ->inventarioActual
                    ->predio;

                return [
                    'predio' => $predio->nombre,
                    'stock' => $grupo->count(),
                ];

            })
            ->values();

        // Total disponible con ubicación vigente.
        $stockTotal = $stockPorPredio->sum('stock');

        return view('vehiculos.index', compact(
            'vehiculos',
            'stockPorPredio',
            'stockTotal'
        ));
    }

    /** Cambia el precio comercial únicamente tras un avalúo aprobado. */
    public function actualizarPrecio(Request $request, $id)
    {
        $datos = $request->validate([
            'id_avaluo' => 'required|integer',
            'confirmar' => 'required|accepted',
        ]);
        try {
            DB::connection('oracle')->transaction(function () use ($id, $datos) {
                $db = DB::connection('oracle');
                $usuarioId = (int)(auth()->user()->id_usuario ?? auth()->id());
                $admin = $db->selectOne("SELECT U.ID_USUARIO FROM USUARIO U JOIN ROL R ON R.ID_ROL = U.ID_ROL WHERE U.ID_USUARIO = ? AND U.ACTIVO = 1 AND R.NOMBRE = 'ADMINISTRADOR'", [$usuarioId]);
                if (!$admin) { throw new RuntimeException('Solo el administrador puede actualizar precios.'); }
                $v = $db->selectOne('SELECT ID_VEHICULO, CONDICION, ESTADO, PRECIO_LISTA FROM VEHICULO WHERE ID_VEHICULO = ? FOR UPDATE NOWAIT', [(int)$id]);
                if (!$v || strtoupper((string)$v->condicion) !== 'USADO' || strtoupper((string)$v->estado) !== 'DISPONIBLE') {
                    throw new RuntimeException('Solo puede actualizarse un vehículo usado DISPONIBLE.');
                }
                $a = $db->selectOne('SELECT ID_AVALUO, ID_INSPECCION, VALOR_ESTIMADO, ESTADO FROM AVALUO WHERE ID_AVALUO = ? AND ID_VEHICULO = ?', [(int)$datos['id_avaluo'], (int)$id]);
                if (!$a || strtoupper((string)$a->estado) !== 'APROBADO') {
                    throw new RuntimeException('Se requiere un avalúo APROBADO del vehículo.');
                }
                $ultima = $db->selectOne('SELECT ID_INSPECCION, RESULTADO FROM (SELECT ID_INSPECCION, RESULTADO FROM INSPECCION WHERE ID_VEHICULO = ? ORDER BY FECHA_INSPECCION DESC, ID_INSPECCION DESC) WHERE ROWNUM = 1', [(int)$id]);
                if (!$ultima || (int)$ultima->id_inspeccion !== (int)$a->id_inspeccion || strtoupper((string)$ultima->resultado) !== 'APROBADA') {
                    throw new RuntimeException('El avalúo debe corresponder a la última inspección aprobada.');
                }
                $reservas = $db->selectOne("SELECT COUNT(*) AS TOTAL FROM RESERVA WHERE ID_VEHICULO = ? AND ESTADO IN ('PENDIENTE', 'CONFIRMADA')", [(int)$id]);
                if ((int)$reservas->total > 0) { throw new RuntimeException('No se puede modificar el precio con reservas activas.'); }
                $nuevo = (float)$a->valor_estimado;
                if ($nuevo <= 0) { throw new RuntimeException('El avalúo debe ser mayor que cero.'); }
                if (abs((float)$v->precio_lista - $nuevo) < 0.005) {
                    throw new RuntimeException('El precio comercial ya coincide con el avalúo aprobado. No es necesario actualizarlo nuevamente.');
                }
                $db->update('UPDATE VEHICULO SET PRECIO_LISTA = ? WHERE ID_VEHICULO = ?', [$nuevo, (int)$id]);
            });
            return back()->with('success', 'Precio comercial actualizado con el avalúo aprobado.');
        } catch (Throwable $e) {
            report($e);
            return back()->withErrors(['precio' => $e instanceof RuntimeException ? $e->getMessage() : 'No se pudo actualizar el precio.']);
        }
    }

}
