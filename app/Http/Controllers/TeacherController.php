<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class TeacherController extends Controller
{
    // Mostrar todos los maestros
    public function index()
{
    $teachers = User::where('rol', 'maestro')->with('grupo')->get();
    return view('teachers.index', compact('teachers'));
}

    // Mostrar formulario para crear un nuevo maestro
    public function create()
    {
        return view('teachers.create');
    }

    // Guardar nuevo maestro
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|unique:users,correo',
            'specialty' => 'nullable|string',
            'subject' => 'nullable|string',
        ]);

        User::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'rol' => 'maestro',
            'specialty' => $request->specialty,
            'subject' => $request->subject,
            'password' => bcrypt('password123'), // puedes hacer un campo editable si deseas
            'activo' => true,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Maestro registrado correctamente.');
    }

    // Mostrar formulario para editar maestro
    public function edit($id)
    {
        $teacher = User::where('_id', $id)->where('rol', 'maestro')->firstOrFail();
        return view('teachers.edit', compact('teacher'));
    }

    // Actualizar maestro
    public function update(Request $request, $id)
    {
        $teacher = User::where('_id', $id)->where('rol', 'maestro')->firstOrFail();

        $request->validate([
            'nombre' => 'required|string|max:100',
            'correo' => 'required|email|unique:users,correo,' . $id . ',_id',
            'specialty' => 'nullable|string',
            'subject' => 'nullable|string',
        ]);

        $teacher->update([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'specialty' => $request->specialty,
            'subject' => $request->subject,
        ]);

        return redirect()->route('teachers.index')->with('success', 'Maestro actualizado correctamente.');
    }

    // Eliminar maestro
    public function destroy($id)
    {
        $teacher = User::where('_id', $id)->where('rol', 'maestro')->firstOrFail();
        $teacher->delete();

        return redirect()->route('teachers.index')->with('success', 'Maestro eliminado correctamente.');
    }
}
