<?php

namespace App\Http\Controllers;

use App\Models\Usuario;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with('rol')
            ->orderBy('id_usuario', 'asc')
            ->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function show($id)
    {
        $usuario = Usuario::with('rol')
            ->findOrFail($id);

        return view('usuarios.show', compact('usuario'));
    }
}
