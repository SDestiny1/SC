<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subject;
use App\Models\Group;
use App\Models\Career; // Asegúrate de que Carrera esté correctamente importado
use Carbon\Carbon;

class TeacherController extends Controller

{
 public function index(Request $request)
{
    $query = User::where('rol', 'docente')
               ->with(['grupo' => function($q) {
                   $q->with('carrera');
               }, 'materia']);

    // Búsqueda
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('nombre', 'like', "%$search%")
              ->orWhere('apellidoPaterno', 'like', "%$search%")
              ->orWhere('apellidoMaterno', 'like', "%$search%")
              ->orWhere('_id', 'like', "%$search%");
        });
    }
    
    // Filtro por departamento (corregido)
    if ($request->filled('department')) {
        $query->whereHas('grupo.carrera', function($q) use ($request) {
            $q->where('_id', $request->department);
        });
    }
    
    // Filtro por estatus (simplificado)
    if ($request->filled('status')) {
        $query->where('activo', $request->status == 'active');
    }
    
    // Filtro por materia (corregido)
    if ($request->filled('subject')) {
        $query->where('materiaID', $request->subject);
    }
        // Estadísticas
        $totalTeachers = User::where('rol', 'docente')->count();
        $activeTeachers = User::where('rol', 'docente')->where('activo', true)->count();
        $totalSubjects = Subject::where('activo', true)->count();
        $totalGroups = Group::where('activo', true)->count();
        
        // Obtener opciones para filtros
        $departments = Career::where('activo', true)
                           ->orderBy('nombreCarrera')
                           ->pluck('nombreCarrera', '_id');
        
        $subjects = Subject::where('activo', true)
                         ->orderBy('nombre')
                         ->pluck('nombre', '_id');
        
        // Paginación
        $teachers = $query->paginate(12);
        
        return view('teachers.index', compact(
            'teachers',
            'totalTeachers',
            'activeTeachers',
            'totalSubjects',
            'totalGroups',
            'departments',
            'subjects'
        ));
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
    $teacher = User::where('_id', $id)->where('rol', 'docente')->firstOrFail();
    
    // Cambiar el estatus en lugar de eliminar
    $teacher->update([
        'activo' => !$teacher->activo // Invierte el estado actual
    ]);
    
    return back()->with('success', $teacher->activo ? 'Docente reactivado correctamente' : 'Docente desactivado correctamente');
}

public function show($id)
{
    $teacher = User::where('_id', $id)
        ->where('rol', 'docente')
        ->with(['grupo.carrera', 'materia'])
        ->firstOrFail();

    // Si es un objeto UTCDateTime de MongoDB
    if ($teacher->fechaRegistro instanceof \MongoDB\BSON\UTCDateTime) {
        $teacher->fechaRegistro = Carbon::instance($teacher->fechaRegistro->toDateTime());
    }
    
    if ($teacher->fechaNacimiento instanceof \MongoDB\BSON\UTCDateTime) {
        $teacher->fechaNacimiento = Carbon::instance($teacher->fechaNacimiento->toDateTime());
    }

    return view('teachers.show', compact('teacher'));
}
}
