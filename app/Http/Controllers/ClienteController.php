<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('id_cliente', 'asc')->get();

        return view('clientes.index', compact('clientes'));
    }

    public function create()
    {
        return view('clientes.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'dpi' => ['required', 'digits:13', Rule::unique('oracle.CLIENTE', 'DPI')],
            'nit' => ['nullable', 'string', 'max:20', Rule::unique('oracle.CLIENTE', 'NIT')],
            'email' => ['nullable', 'email', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ]);

        // El trigger TRG_CLIENTE_BI debe asignar ID_CLIENTE con SQ_CLIENTE.
        // No usamos Eloquent::create() para evitar RETURNING del ID en Oracle 11g.
        $id = DB::connection('oracle')->transaction(function () use ($datos) {
            DB::connection('oracle')->table('CLIENTE')->insert([
                'NOMBRE' => $datos['nombre'],
                'APELLIDO' => $datos['apellido'],
                'TIPO_CLIENTE' => 'INDIVIDUAL',
                'DPI' => $datos['dpi'],
                'NIT' => $datos['nit'] ?? null,
                'EMAIL' => $datos['email'] ?? null,
                'TELEFONO' => $datos['telefono'] ?? null,
                'DIRECCION' => $datos['direccion'] ?? null,
                'FECHA_REGISTRO' => DB::raw('SYSDATE'),
                'ACTIVO' => 1,
            ]);
            // CURRVAL es seguro dentro de la misma sesión Oracle, tras NEXTVAL del trigger.
            $registro = DB::connection('oracle')->selectOne('SELECT SQ_CLIENTE.CURRVAL AS ID FROM DUAL');
            return $registro->id ?? $registro->ID;
        });

        return redirect()->route('clientes.show', $id)
            ->with('success', 'Cliente registrado correctamente.');
    }

    public function show($id)
    {
        $cliente = Cliente::findOrFail($id);

        return view('clientes.show', compact('cliente'));
    }

    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);

        return view('clientes.edit', compact('cliente'));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $datos = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'dpi' => ['nullable', 'string', 'max:20'],
            'nit' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ]);

        $cliente->fill($datos);
        $cliente->save();

        return redirect()
            ->route('clientes.show', $cliente->id_cliente)
            ->with('success', 'Cliente actualizado correctamente.');
    }

    
public function toggleEstado($id)
{
    $cliente = Cliente::findOrFail($id);

    $nuevoEstado = (int) $cliente->activo === 1 ? 0 : 1;

    $cliente->activo = $nuevoEstado;
    $cliente->save();

    $mensaje = $nuevoEstado === 1
        ? 'Cliente activado correctamente.'
        : 'Cliente desactivado correctamente.';

    return redirect()
        ->route('clientes.index')
        ->with('success', $mensaje);
}

}
