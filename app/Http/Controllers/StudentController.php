<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Group;
use Carbon\Carbon;
use App\Models\Career; 
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;


class StudentController extends Controller
{
   public function index(Request $request)
{
    $query = User::where('rol', 'alumno')
                ->with(['grupo' => function($q) {
                    $q->with('carrera');
                }]);

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
    if ($request->filled('carreraID')) {
        $query->whereHas('grupo', function($q) use ($request) {
            $q->where('carreraID', $request->carreraID);
        });
    }
    
    // Filtro por semestre (corregido)
    if ($request->filled('semestre')) {
        $query->whereHas('grupo', function($q) use ($request) {
            $q->where('semestre', (int)$request->semestre);
        });
    }
    
    // Filtro por estatus (corregido)
    if ($request->filled('estatus')) {
        $query->where('activo', $request->estatus == 'active');
    }
    
    // Obtener opciones para filtros
    $carreras = Career::where('activo', true)
                     ->orderBy('nombreCarrera')
                     ->pluck('nombreCarrera', '_id');
    
    $semestres = Group::distinct('semestre')
                     ->orderBy('semestre')
                     ->pluck('semestre');
    
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
    
    // Cambiar el estatus en lugar de eliminar
    $student->update([
        'activo' => !$student->activo // Invierte el estado actual
    ]);
    
    return back()->with('success', $student->activo ? 'Alumno reactivado correctamente' : 'Alumno desactivado correctamente');
}

public function show($id)
{
    try {
        $student = User::where('_id', $id)
            ->where('rol', 'alumno')
            ->with(['grupo' => function($query) {
                $query->with('carrera');
            }])
            ->firstOrFail();

        if ($student->fechaNacimiento instanceof \MongoDB\BSON\UTCDateTime) {
            $student->fechaNacimiento = Carbon::instance($student->fechaNacimiento->toDateTime());
        }

        if ($student->fechaRegistro instanceof \MongoDB\BSON\UTCDateTime) {
            $student->fechaRegistro = Carbon::instance($student->fechaRegistro->toDateTime());
        }

        if (request()->ajax()) {
            return view('students._show', compact('student'));
        }

        return view('students.show', compact('student'));

    } catch (\Throwable $e) {
        \Log::error('Error al mostrar alumno: ' . $e->getMessage());
        return response()->json(['error' => 'Error interno al mostrar alumno.'], 500);
    }
}


public function import(Request $request)
{
    $request->validate([
        'file' => 'required|file|mimes:xlsx,xls,csv'
    ]);
    
    try {
        $import = new StudentsImport();
        Excel::import($import, $request->file('file'));
        
        $response = [
            'imported' => $import->getImportedCount(),
            'skipped' => $import->getSkippedCount(),
            'message' => 'Importación completada'
        ];
        
        if ($import->getSkippedCount() > 0) {
            $response['errors'] = $import->getErrors();
        }
        
        return response()->json($response);
        
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Error al procesar el archivo: ' . $e->getMessage(),
            'trace' => env('APP_DEBUG') ? $e->getTrace() : null
        ], 500);
    }
}
}