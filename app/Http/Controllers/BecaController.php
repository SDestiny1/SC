<?php

namespace App\Http\Controllers;

use App\Models\Beca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BecaController extends Controller
{
    public function index(Request $request)
{
    $query = Beca::query();
    
    // Búsqueda general
    if ($request->has('search') && !empty($request->search)) {
        $searchTerm = $request->search;
        $query->where(function($q) use ($searchTerm) {
            $q->where('titulo', 'like', '%' . $searchTerm . '%')
              ->orWhere('institucion', 'like', '%' . $searchTerm . '%')
              ->orWhere('descripcion', 'like', '%' . $searchTerm . '%');
        });
    }
    
    // Filtro por tipo
    if ($request->has('tipo') && !empty($request->tipo)) {
        $query->where('tipo', $request->tipo);
    }
    

    
    // Filtro por estado
    if ($request->has('estado') && !empty($request->estado)) {
        $now = Carbon::now();
        
        if ($request->estado == 'activas') {
            // Becas activas: fecha actual está entre fechaInicio y fechaFin
            $query->where('fechaInicio', '<=', $now)
                  ->where('fechaFin', '>=', $now);
        } elseif ($request->estado == 'proximas') {
            // Becas próximas: fechaInicio es posterior a la fecha actual
            $query->where('fechaInicio', '>', $now);
        }
    }
    
    $becas = $query->where('activo', true)
                ->orderBy('fechaPublicacion', 'desc')
                ->get();
    
    // Si es una petición AJAX, devolver solo el HTML de las tarjetas
    if ($request->ajax()) {
        return view('becas.partials.becas-cards', compact('becas'))->render();
    }
    
    return view('becas.index', compact('becas'));
}

    public function show($id)
    {
        $beca = Beca::findOrFail($id);
        return view('becas.show', compact('beca'));
    }

    public function create()
    {
        return view('becas.create');
    }

    public function store(Request $request)
    {
        // Verificar que el usuario esté autenticado
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Debes iniciar sesión para crear becas/programas.');
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'requisitos' => 'required|array|min:1',
            'requisitos.*' => 'string',
            'documentos' => 'required|array|min:1',
            'documentos.*' => 'string',
            'condicionEspecial' => 'nullable|string',
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after:fechaInicio',
            'institucion' => 'required|string',
            'url' => 'required|url',
            'tipo' => 'required|in:programa,beca'
        ]);

        // Campos específicos según el tipo
        if ($request->tipo === 'beca') {
            $request->validate([
                'monto' => 'required|numeric|min:0',
            ]);
        } else {
            // Para programas, no se requiere monto
            // Los programas usan los mismos campos que las becas, solo sin monto
        }

        $beca = new Beca();
        
        // Asignar campos básicos
        $beca->titulo = $validated['titulo'];
        $beca->descripcion = $validated['descripcion'];
        
        // Asegurar que requisitos y documentos sean arrays
        $requisitos = is_array($validated['requisitos']) ? $validated['requisitos'] : [];
        $documentos = is_array($validated['documentos']) ? $validated['documentos'] : [];
        
        // Filtrar elementos vacíos
        $requisitos = array_filter($requisitos, function($item) {
            return !empty(trim($item));
        });
        $documentos = array_filter($documentos, function($item) {
            return !empty(trim($item));
        });
        
        $beca->requisitos = array_values($requisitos); // Reindexar array
        $beca->documentos = array_values($documentos); // Reindexar array
        
        $beca->condicionEspecial = $validated['condicionEspecial'];
        $beca->fechaInicio = $validated['fechaInicio'];
        $beca->fechaFin = $validated['fechaFin'];
        $beca->institucion = $validated['institucion'];
        $beca->url = $validated['url'];
        $beca->tipo = $validated['tipo'];
        
        // Campos específicos según el tipo
        if ($request->tipo === 'beca') {
            $beca->monto = $request->monto;
        } else {
            // Para programas
            $beca->monto = null; // Los programas no tienen monto
        }
        
        $beca->activo = true;
        
        // Asegurar que se guarde el autorID correctamente
        $user = Auth::user();
        $beca->autorID = $user ? $user->email : 'admin@default.com';
        
        $beca->fechaPublicacion = Carbon::now();
        
        $beca->save();

        // Debug: Verificar que los arrays se guardaron correctamente
        \Log::info('Beca creada:', [
            'id' => $beca->_id,
            'autorID' => $beca->autorID,
            'user_email' => $user ? $user->email : 'no_user',
            'requisitos' => $beca->requisitos,
            'documentos' => $beca->documentos,
            'requisitos_type' => gettype($beca->requisitos),
            'documentos_type' => gettype($beca->documentos)
        ]);

        return redirect()->route('becas.index')->with('success', ucfirst($request->tipo) . ' creado exitosamente!');
    }
}
