<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use App\Models\Carrera;

class StudentController extends Controller
{
    public function index(Request $request)
{
    // Consulta base con relaciones
    $query = User::where('rol', 'alumno')
                ->with(['grupo.carrera']); // Cargar grupo y su carrera
    
    // Búsqueda
    if ($request->has('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('nombre', 'like', "%$search%")
              ->orWhere('apellidoPaterno', 'like', "%$search%")
              ->orWhere('apellidoMaterno', 'like', "%$search%")
              ->orWhere('_id', 'like', "%$search%");
        });
    }
    
    // Filtro por carrera
    if ($request->has('carreraID')) {
        $query->whereHas('grupo', function($q) use ($request) {
            $q->where('carreraID', $request->carreraID);
        });
    }
    
    // Filtro por semestre
    if ($request->has('semestre')) {
        $query->whereHas('grupo', function($q) use ($request) {
            $q->where('semestre', $request->semestre);
        });
    }
    
    // Filtro por estatus
    if ($request->has('estatus')) {
        $estatus = $request->estatus == 'active' ? true : false;
        $query->where('activo', $estatus);
    }
    
    // Obtener opciones para filtros
    $carreras = Carrera::where('activo', true)
                     ->orderBy('nombreCarrera')
                     ->pluck('nombreCarrera', '_id');
    
    $semestres = Group::distinct('semestre')
                     ->orderBy('semestre')
                     ->pluck('semestre');
    
    // Paginación
    $students = $query->paginate(10);
    
    return view('students.index', compact('students', 'carreras', 'semestres'));
}

    public function create()
    {
        $groups = Group::all();
        return view('students.create', compact('groups'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'apellidoPaterno' => 'required|string|max:100',
            'apellidoMaterno' => 'required|string|max:100',
            '_id' => 'required|email|unique:users,_id',
            'password' => 'required|string|min:8',
            'fechaNacimiento' => 'required|date',
            'grupoID' => 'required|exists:groups,_id',
        ]);

        $user = new User([
            '_id' => $request->_id,
            'nombre' => $request->nombre,
            'apellidoPaterno' => $request->apellidoPaterno,
            'apellidoMaterno' => $request->apellidoMaterno,
            'password' => bcrypt($request->password),
            'fechaNacimiento' => $request->fechaNacimiento,
            'rol' => 'alumno',
            'grupoID' => $request->grupoID,
            'activo' => true,
            'fechaRegistro' => now(),
        ]);

        $user->save();

        return redirect()->route('students.index')->with('success', 'Alumno registrado exitosamente.');
    }

    public function destroy($id)
    {
        $student = User::where('_id', $id)->where('rol', 'alumno')->firstOrFail();
        $student->delete();
        return back()->with('success', 'Alumno eliminado correctamente.');
    }

    public function show($id)
{
    $student = User::where('_id', $id)->where('rol', 'alumno')->firstOrFail();
    return view('students.show', compact('student'));
}
}