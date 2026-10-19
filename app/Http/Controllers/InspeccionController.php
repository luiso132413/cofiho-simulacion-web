<?php

namespace App\Http\Controllers;

use App\Models\Inspeccion;
use App\Services\InspeccionService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;

class InspeccionController extends Controller
{
    public function index()
    {
        $inspecciones = Inspeccion::with([
            'vehiculo',
            'usuario'
        ])
        ->orderBy('id_inspeccion', 'desc')
        ->get();

        return view('inspecciones.index', compact('inspecciones'));
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

        return view('inspecciones.show', compact('inspeccion'));
    }

    public function store(Request $request, InspeccionService $service)
    {
        try {
            $id = $service->registrarInspeccion(
                $request->only([
                    'id_vehiculo',
                    'id_usuario',
                    'resultado',
                    'observaciones',
                    'detalles'
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
                    'inspeccion' => 'No se pudo registrar la inspección. Verifica los datos y vuelve a intentarlo.'
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
                    'avaluo' => 'No se pudo registrar el avalúo. Verifica los datos y vuelve a intentarlo.'
                ]);
        }
    }
}
