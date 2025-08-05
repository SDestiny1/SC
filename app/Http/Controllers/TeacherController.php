<?php

namespace App\Http\Controllers;

use App\Models\ClassSchedule;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subject;
use App\Models\Group;
use App\Models\Career; // Asegúrate de que Carrera esté correctamente importado
use Carbon\Carbon;
use App\Imports\DocentesImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Teacher; // Asegúrate de que Teacher esté correctamente importado

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

    if ($teacher->fechaRegistro instanceof \MongoDB\BSON\UTCDateTime) {
        $teacher->fechaRegistro = Carbon::instance($teacher->fechaRegistro->toDateTime());
    }

    if ($teacher->fechaNacimiento instanceof \MongoDB\BSON\UTCDateTime) {
        $teacher->fechaNacimiento = Carbon::instance($teacher->fechaNacimiento->toDateTime());
    }

    // Obtener materias del departamento solo si el docente tiene grupo y carrera
    $materiasDepartamento = collect();
    if ($teacher->grupo && $teacher->grupo->carrera) {
        $materiasDepartamento = Subject::where('carreraID', $teacher->grupo->carrera->_id)->get();
    }

    return view('teachers.show', compact('teacher', 'materiasDepartamento'));
}

public function assignSchedule(Request $request)
{
    try {
        $teacherId = $request->input('teacher_id');  // debe coincidir con lo que envías
        $subjectId = $request->input('subject_id');
        $scheduleData = $request->input('schedules'); // arreglo con días y horarios

        $teacher = User::where('_id', $teacherId)->where('rol', 'docente')->first();
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => "Docente no encontrado con ID: $teacherId"
            ]);
        }

        // Asignar materia si no está asignada
        if (!$teacher->materiaID || !in_array($subjectId, (array) $teacher->materiaID)) {
            $teacher->push('materiaID', $subjectId);
        }

        foreach ($scheduleData as $dia => $horas) {
            // $horas es un array de strings con formato "HH:MM - HH:MM"
            foreach ($horas as $hora) {
                list($horaInicio, $horaFin) = array_map('trim', explode('-', $hora));

                $horarioExistente = ClassSchedule::where('grupoID', $teacher->grupoID)
                    ->where('diaSemana', strtolower($dia))
                    ->first();

                if ($horarioExistente) {
                    // Verificar conflicto: misma hora inicio y fin
                    $conflicto = collect($horarioExistente->clases)->first(function ($clase) use ($horaInicio, $horaFin) {
                        return $clase['horaInicio'] === $horaInicio && $clase['horaFin'] === $horaFin;
                    });

                    if (!$conflicto) {
                        $horarioExistente->push('clases', [
                            'horaInicio' => $horaInicio,
                            'horaFin' => $horaFin,
                            'materiaID' => $subjectId,
                            'docenteID' => $teacherId,
                            'activo' => true,
                        ]);
                    }
                } else {
                    // Crear documento para ese día si no existe aún
                    ClassSchedule::create([
                        'grupoID' => $teacher->grupoID,
                        'cicloID' => '2025-A', // ajustar si es dinámico
                        'diaSemana' => strtolower($dia),
                        'clases' => [
                            [
                                'horaInicio' => $horaInicio,
                                'horaFin' => $horaFin,
                                'materiaID' => $subjectId,
                                'docenteID' => $teacherId,
                                'activo' => true,
                            ]
                        ]
                    ]);
                }
            }
        }

        return response()->json(['success' => true, 'message' => 'Horario asignado con éxito.']);

    } catch (\Exception $e) {
        return response()->json(['success' => false, 'message' => 'Error al asignar el horario: ' . $e->getMessage()]);
    }
}



public function import(Request $request)
{
    try {
        if (!$request->hasFile('file')) {
            return response()->json(['message' => 'Archivo no proporcionado.'], 400);
        }

        $import = new DocentesImport();
        Excel::import($import, $request->file('file'));

        return response()->json([
            'imported' => $import->imported,
            'skipped' => $import->skipped,
            'errors' => $import->skippedDetails,  // ahora sí se envía el detalle de errores
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'message' => 'Error interno',
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
}

public function createSchedule($teacherId)
{
    $teacher = Teacher::findOrFail($teacherId);
    $materias = Subject::where('carreraID', $teacher->grupo->carrera->_id)->get();

    return view('teachers.schedule.create', compact('teacher', 'materias'));
}




}
