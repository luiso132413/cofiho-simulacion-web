<?php

namespace App\Http\Controllers;

use App\Models\Persona;
use App\Models\Rol;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::with(['rol', 'persona'])
            ->orderBy('id_usuario', 'asc')
            ->get();

        return view('usuarios.index', compact('usuarios'));
    }

    public function show($id)
    {
        $usuario = Usuario::with(['rol', 'persona'])
            ->findOrFail($id);

        return view('usuarios.show', compact('usuario'));
    }

    public function create()
    {
        $roles = Rol::orderBy('id_rol')->get();

        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'dpi' => [
                'required',
                'regex:/^[0-9]{13}$/',
                Rule::unique('oracle.PERSONA', 'DPI'),
            ],
            'telefono' => ['nullable', 'string', 'max:25'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('oracle.USUARIO', 'EMAIL'),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'id_rol' => [
                'required',
                'integer',
                Rule::exists('oracle.ROL', 'ID_ROL'),
            ],
        ]);

        DB::connection('oracle')->transaction(function () use ($datos) {
            $idPersona = DB::connection('oracle')
                ->table('PERSONA')
                ->insertGetId([
                    'NOMBRES' => $datos['nombres'],
                    'APELLIDOS' => $datos['apellidos'],
                    'DPI' => $datos['dpi'],
                    'TELEFONO' => $datos['telefono'] ?? null,
                ], 'ID_PERSONA');

            DB::connection('oracle')
                ->table('USUARIO')
                ->insert([
                    'ID_PERSONA' => $idPersona,
                    'ID_ROL' => $datos['id_rol'],
                    'NOMBRE' => trim(
                        $datos['nombres'] . ' ' . $datos['apellidos']
                    ),
                    'EMAIL' => $datos['email'],
                    'PASSWORD_HASH' => Hash::make($datos['password']),
                    'ACTIVO' => 1,
                    'FECHA_CREACION' => DB::raw('SYSDATE'),
                ]);
        });

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario registrado correctamente.');
    }

    public function edit($id)
    {
        $usuario = Usuario::with('persona')->findOrFail($id);
        $roles = Rol::orderBy('id_rol')->get();

        return view('usuarios.edit', compact('usuario', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::with('persona')->findOrFail($id);

        $reglas = [
            'nombres' => ['required', 'string', 'max:100'],
            'apellidos' => ['required', 'string', 'max:100'],
            'dpi' => [
                'required',
                'regex:/^[0-9]{13}$/',
                Rule::unique('oracle.PERSONA', 'DPI')
                    ->ignore($usuario->id_persona, 'ID_PERSONA'),
            ],
            'telefono' => ['nullable', 'string', 'max:25'],
            'email' => [
                'required',
                'email',
                'max:150',
                Rule::unique('oracle.USUARIO', 'EMAIL')
                    ->ignore($usuario->id_usuario, 'ID_USUARIO'),
            ],
            'id_rol' => [
                'required',
                'integer',
                Rule::exists('oracle.ROL', 'ID_ROL'),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ];

        $datos = $request->validate($reglas);

        if (
            (int) $usuario->id_usuario === (int) auth()->id()
            && (int) $datos['id_rol'] !== (int) $usuario->id_rol
        ) {
            return back()
                ->withErrors([
                    'id_rol' => 'No puedes cambiar tu propio rol.',
                ])
                ->withInput();
        }

        DB::connection('oracle')->transaction(function () use ($usuario, $datos) {
            $datosPersona = [
                'NOMBRES' => $datos['nombres'],
                'APELLIDOS' => $datos['apellidos'],
                'DPI' => $datos['dpi'],
                'TELEFONO' => $datos['telefono'] ?? null,
            ];

            if ($usuario->id_persona) {
                DB::connection('oracle')
                    ->table('PERSONA')
                    ->where('ID_PERSONA', $usuario->id_persona)
                    ->update($datosPersona);

                $idPersona = $usuario->id_persona;
            } else {
                $idPersona = DB::connection('oracle')
                    ->table('PERSONA')
                    ->insertGetId($datosPersona, 'ID_PERSONA');
            }

            $datosUsuario = [
                'ID_PERSONA' => $idPersona,
                'ID_ROL' => $datos['id_rol'],
                'NOMBRE' => trim(
                    $datos['nombres'] . ' ' . $datos['apellidos']
                ),
                'EMAIL' => $datos['email'],
            ];

            if (!empty($datos['password'])) {
                $datosUsuario['PASSWORD_HASH'] =
                    Hash::make($datos['password']);
            }

            DB::connection('oracle')
                ->table('USUARIO')
                ->where('ID_USUARIO', $usuario->id_usuario)
                ->update($datosUsuario);
        });

        return redirect()
            ->route('usuarios.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }

    public function toggleEstado($id)
    {
        $usuario = Usuario::findOrFail($id);

        if ((int) $usuario->id_usuario === (int) auth()->id()) {
            return back()->with(
                'error',
                'No puedes desactivar tu propia cuenta.'
            );
        }

        $nuevoEstado = (int) $usuario->activo === 1 ? 0 : 1;

        DB::connection('oracle')
            ->table('USUARIO')
            ->where('ID_USUARIO', $usuario->id_usuario)
            ->update(['ACTIVO' => $nuevoEstado]);

        return redirect()
            ->route('usuarios.index')
            ->with(
                'success',
                $nuevoEstado
                    ? 'Usuario activado correctamente.'
                    : 'Usuario desactivado correctamente.'
            );
    }
}