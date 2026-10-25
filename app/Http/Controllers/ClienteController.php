<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index()
    {
        $clientes = Cliente::orderBy('id_cliente', 'asc')->get();

        return view('clientes.index', compact('clientes'));
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
            'tipo_cliente' => [
                'required',
                Rule::in(['INDIVIDUAL', 'EMPRESARIAL']),
            ],
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
