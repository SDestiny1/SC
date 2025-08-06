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
    public function __construct()
    {
        $this->middleware('auth');
    }
    
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
        $groups = Group::with('carrera')->where('activo', true)->get();
        $carreras = Career::where('activo', true)->orderBy('nombreCarrera')->get();
        return view('students.create', compact('groups', 'carreras'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'apellidoPaterno' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'apellidoMaterno' => 'required|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            '_id' => 'required|email|unique:users,_id',
            'password' => 'required|string|min:8|confirmed',
            'fechaNacimiento' => 'required|date|before:today|after:1900-01-01',
            'grupoID' => 'required|exists:groups,_id',
            'genero' => 'nullable|in:masculino,femenino,otro',
            'telefono' => 'nullable|string|max:20',
            'matricula' => 'nullable|string|max:20|unique:users,matricula',
        ], [
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'apellidoPaterno.regex' => 'El apellido paterno solo puede contener letras y espacios.',
            'apellidoMaterno.regex' => 'El apellido materno solo puede contener letras y espacios.',
            'fechaNacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'fechaNacimiento.after' => 'La fecha de nacimiento debe ser posterior a 1900.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        // Validar edad (entre 15 y 100 años)
        $fechaNacimiento = \Carbon\Carbon::parse($request->fechaNacimiento);
        $edad = $fechaNacimiento->age;
        
        if ($edad < 15 || $edad > 100) {
            return back()->withErrors(['fechaNacimiento' => 'La edad debe estar entre 15 y 100 años.'])->withInput();
        }

        // Generar matrícula automáticamente si no se proporciona
        $matricula = $request->matricula;
        if (empty($matricula)) {
            $year = date('Y');
            $lastStudent = User::where('matricula', 'like', $year . '%')
                              ->orderBy('matricula', 'desc')
                              ->first();
            
            if ($lastStudent) {
                $lastNumber = intval(substr($lastStudent->matricula, -4));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
            
            $matricula = $year . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }

        // Verificar que la matrícula sea única
        if (User::where('matricula', $matricula)->exists()) {
            return back()->withErrors(['matricula' => 'La matrícula ya existe en el sistema.'])->withInput();
        }

        try {
            $user = new User([
                '_id' => $request->_id,
                'nombre' => ucwords(strtolower($request->nombre)),
                'apellidoPaterno' => ucwords(strtolower($request->apellidoPaterno)),
                'apellidoMaterno' => ucwords(strtolower($request->apellidoMaterno)),
                'password' => bcrypt($request->password),
                'fechaNacimiento' => $fechaNacimiento,
                'genero' => $request->genero,
                'telefono' => $request->telefono,
                'matricula' => $matricula,
                'rol' => 'alumno',
                'grupoID' => $request->grupoID,
                'activo' => true,
                'fechaRegistro' => now(),
            ]);

            $user->save();

            return redirect()->route('students.index')
                           ->with('success', 'Alumno registrado exitosamente. Matrícula: ' . $matricula);

        } catch (\Exception $e) {
            \Log::error('Error al registrar alumno: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Error al registrar el alumno. Por favor, intente nuevamente.'])->withInput();
        }
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