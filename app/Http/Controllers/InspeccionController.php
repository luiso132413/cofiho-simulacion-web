<?php

namespace App\Http\Controllers;

use App\Models\Inspeccion;
use App\Services\InspeccionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Throwable;

class InspeccionController extends Controller
{

    private function catalogos(): array
    {
        $db = DB::connection('oracle');
        $vehiculos = $db->table('VEHICULO as V')
            ->join('MODELO as M', 'M.ID_MODELO', '=', 'V.ID_MODELO')
            ->join('MARCA as B', 'B.ID_MARCA', '=', 'M.ID_MARCA')
            ->where('V.CONDICION', 'USADO')
            ->select('V.ID_VEHICULO', 'V.PLACA', 'V.VIN', 'V.ESTADO', 'M.NOMBRE as MODELO', 'B.NOMBRE as MARCA')
            ->orderBy('B.NOMBRE')->orderBy('M.NOMBRE')->get();

        $inspectores = $db->table('USUARIO as U')
            ->join('ROL as R', 'R.ID_ROL', '=', 'U.ID_ROL')
            ->leftJoin('PERSONA as P', 'P.ID_PERSONA', '=', 'U.ID_PERSONA')
            ->where('U.ACTIVO', 1)
            ->whereIn('R.NOMBRE', ['INSPECTOR', 'ADMINISTRADOR'])
            ->select('U.ID_USUARIO', 'U.NOMBRE', 'P.NOMBRES', 'P.APELLIDOS')
            ->orderBy('U.NOMBRE')->get();

        return [$vehiculos, $inspectores];
    }

    public function index()
    {
        $inspecciones = Inspeccion::with([
            'vehiculo',
            'usuario'
        ])
        ->orderBy('id_inspeccion', 'desc')
        ->get();

        [$vehiculos, $inspectores] = $this->catalogos();
        return view('inspecciones.index', compact('inspecciones', 'vehiculos', 'inspectores'));
    }

    public function show($id)
    {
        $inspeccion = Inspeccion::with([
            'vehiculo',
            'usuario',
            'detalles',
            'avaluos'
        ])
        ->findOrFail($id);

        [$vehiculos, $inspectores] = $this->catalogos();
        $vehiculoInfo = $vehiculos->firstWhere('id_vehiculo', $inspeccion->id_vehiculo);
        $ultima = DB::connection('oracle')->selectOne(
            'SELECT ID_INSPECCION FROM (SELECT ID_INSPECCION FROM INSPECCION
             WHERE ID_VEHICULO = ? ORDER BY FECHA_INSPECCION DESC, ID_INSPECCION DESC)
             WHERE ROWNUM = 1', [$inspeccion->id_vehiculo]
        );
        $esUltimaInspeccion = $ultima && (int) $ultima->id_inspeccion === (int) $inspeccion->id_inspeccion;
        return view('inspecciones.show', compact('inspeccion', 'inspectores', 'vehiculoInfo', 'esUltimaInspeccion'));
    }

    public function store(Request $request, InspeccionService $service)
    {
        try {
            $id = $service->registrarInspeccion(
                $request->only([
                    'id_vehiculo',
                    'id_usuario',
                    'observaciones',
                ])
            );

            return redirect()
                ->route('inspecciones.show', $id)
                ->with('success', 'Inspección registrada correctamente.');

        } catch (ValidationException $e) {
            throw $e;

        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'inspeccion' => $e instanceof \RuntimeException ? $e->getMessage() : 'No se pudo registrar la inspección.'
                ]);
        }
    }

    public function finalizar(Request $request, InspeccionService $service, $id)
    {
        try {
            $service->finalizarInspeccion((int) $id, $request->only([
                'resultado', 'observaciones', 'detalles'
            ]));
            return redirect()->route('inspecciones.show', $id)
                ->with('success', 'Inspección finalizada correctamente.');
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            report($e);
            return back()->withInput()->withErrors([
                'inspeccion' => $e instanceof \RuntimeException
                    ? $e->getMessage()
                    : 'No se pudo finalizar la inspección.'
            ]);
        }
    }

    public function storeAvaluo(
        Request $request,
        InspeccionService $service
    ) {
        try {
            $id = $service->registrarAvaluo(
                $request->only([
                    'id_inspeccion',
                    'id_usuario',
                    'valor_estimado',
                    'estado',
                    'observaciones'
                ])
            );

            return redirect()
                ->route(
                    'inspecciones.show',
                    $request->input('id_inspeccion')
                )
                ->with('success', 'Avalúo registrado correctamente.');

        } catch (ValidationException $e) {
            throw $e;

        } catch (Throwable $e) {
            report($e);

            return back()
                ->withInput()
                ->withErrors([
                    'avaluo' => $e instanceof \RuntimeException ? $e->getMessage() : 'No se pudo registrar el avalúo.'
                ]);
        }
    }
}
