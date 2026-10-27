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
        ->where('estado', 'DISPONIBLE')
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

    /** Formulario de alta, exclusivo para administradores. */
    public function create()
    {
        $this->autorizarRegistro();
        $db = DB::connection('oracle');
        return view('vehiculos.create', [
            'marcas' => $db->table('MARCA')->orderBy('NOMBRE')->get(),
            'modelos' => $db->table('MODELO')->orderBy('NOMBRE')->get(),
            'tipos' => $db->table('TIPO_VEHICULO')->orderBy('NOMBRE')->get(),
            'predios' => $db->table('PREDIO')->where('ACTIVO', 1)->orderBy('NOMBRE')->get(),
        ]);
    }

    private function autorizarRegistro(): void
    {
        $id = (int) (auth()->user()->id_usuario ?? auth()->id());
        $permitido = DB::connection('oracle')->table('USUARIO as U')
            ->join('ROL as R', 'R.ID_ROL', '=', 'U.ID_ROL')
            ->where('U.ID_USUARIO', $id)->where('U.ACTIVO', 1)
            ->where('R.NOMBRE', 'ADMINISTRADOR')->exists();
        abort_unless($permitido, 403);
    }

    /** Alta atómica: vehículo + primera ubicación. */
    public function store(Request $request)
    {
        $this->autorizarRegistro();
        $request->merge([
            'motor' => trim((string)$request->input('motor')),
            'placa' => strtoupper(trim((string)$request->input('placa'))),
            'color' => trim((string)$request->input('color')),
        ]);
        $datos = $request->validate([
            'motor' => 'required|string|max:60',
            'placa' => 'nullable|string|max:20',
            'id_marca' => 'required|integer|exists:oracle.MARCA,ID_MARCA',
            'id_modelo' => 'required|integer|exists:oracle.MODELO,ID_MODELO',
            'id_tipo_vehiculo' => 'required|integer|exists:oracle.TIPO_VEHICULO,ID_TIPO_VEHICULO',
            'anio' => 'required|integer|between:1900,2100',
            'color' => 'required|string|max:60',
            'condicion' => 'required|in:NUEVO,USADO',
            'precio_lista' => 'required|numeric|min:0',
            'id_predio' => 'required|integer|exists:oracle.PREDIO,ID_PREDIO',
            'ubicacion' => 'nullable|string|max:100',
        ]);
        try {
            DB::connection('oracle')->transaction(function () use ($datos) {
                $db = DB::connection('oracle');
                $modelo = $db->table('MODELO')->where('ID_MODELO', $datos['id_modelo'])
                    ->where('ID_MARCA', $datos['id_marca'])->exists();
                if (!$modelo) throw new RuntimeException('El modelo no pertenece a la marca seleccionada.');
                if (!$db->table('PREDIO')->where('ID_PREDIO', $datos['id_predio'])->where('ACTIVO', 1)->exists()) {
                    throw new RuntimeException('El predio seleccionado no está activo.');
                }
                // Reservar un ID mediante la secuencia Oracle y generar un VIN interno único.
                // El trigger respeta el ID explícito y no consume otro valor de la secuencia.
                $secuencia = $db->selectOne('SELECT SQ_VEHICULO.NEXTVAL AS ID FROM DUAL');
                $id = (int) $secuencia->id;
                $vin = 'COFIHO' . str_pad((string) $id, 11, '0', STR_PAD_LEFT);
                if (strlen($vin) !== 17) {
                    throw new RuntimeException('No se pudo generar un VIN interno válido.');
                }
                $db->table('VEHICULO')->insert([
                    'ID_VEHICULO' => $id,
                    'VIN' => $vin,
                    'MOTOR' => $datos['motor'],
                    'PLACA' => $datos['placa'] ?: null,
                    'ANIO' => (int) $datos['anio'],
                    'ID_MODELO' => (int) $datos['id_modelo'],
                    'ID_TIPO_VEHICULO' => (int) $datos['id_tipo_vehiculo'],
                    'COLOR' => $datos['color'],
                    'CONDICION' => $datos['condicion'],
                    'ESTADO' => $datos['condicion'] === 'USADO' ? 'TALLER' : 'DISPONIBLE',
                    'PRECIO_LISTA' => $datos['precio_lista'],
                    'FECHA_REGISTRO' => DB::raw('SYSDATE'),
                ]);
                $db->table('INVENTARIO_PREDIO')->insert([
                    'ID_VEHICULO' => (int)$id,
                    'ID_PREDIO' => (int)$datos['id_predio'],
                    'UBICACION' => $datos['ubicacion'] ?? null,
                    'FECHA_INGRESO' => DB::raw('SYSDATE'),
                ]);
            });
            return redirect()->route('vehiculos.index')->with('success', 'Vehículo registrado y asignado al predio correctamente.');
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->withErrors(['registro' => $e instanceof RuntimeException ? $e->getMessage() : 'No se pudo registrar el vehículo. Revisa los datos e inténtalo nuevamente.']);
        }
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
                if (!$v || strtoupper((string)$v->condicion) !== 'USADO' || !in_array(strtoupper((string)$v->estado), ['DISPONIBLE', 'TALLER'], true)) {
                    throw new RuntimeException('Solo puede procesarse un vehículo usado en estado DISPONIBLE o TALLER.');
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
                if (strtoupper((string)$v->estado) === 'DISPONIBLE' && abs((float)$v->precio_lista - $nuevo) < 0.005) {
                    throw new RuntimeException('El precio comercial ya coincide con el avalúo aprobado. No es necesario actualizarlo nuevamente.');
                }
                $estadoFinal = 'DISPONIBLE';
                $db->update('UPDATE VEHICULO SET PRECIO_LISTA = ?, ESTADO = ? WHERE ID_VEHICULO = ?', [$nuevo, $estadoFinal, (int)$id]);
            });
            return back()->with('success', 'Precio comercial confirmado y vehículo habilitado para comercialización.');
        } catch (Throwable $e) {
            report($e);
            return back()->withErrors(['precio' => $e instanceof RuntimeException ? $e->getMessage() : 'No se pudo actualizar el precio.']);
        }
    }

}
