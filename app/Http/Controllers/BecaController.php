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
    
    // Filtro por tipo de beca
    if ($request->has('tipo_beca')) {
        if ($request->tipo_beca == 'con_beneficio') {
            $query->where('monto', '>', 0);
        } elseif ($request->tipo_beca == 'sin_beneficio') {
            $query->where('monto', '<=', 0)->orWhereNull('monto');
        }
    }
    
    // Filtro por promedio
    if ($request->has('promedio')) {
        if ($request->promedio == 'con_promedio') {
            $query->whereNotNull('promedioMinimo')->where('promedioMinimo', '>', 0);
        } elseif ($request->promedio == 'sin_promedio') {
            $query->whereNull('promedioMinimo')->orWhere('promedioMinimo', '<=', 0);
        }
    }
    
    // Filtro por estado
    if ($request->has('estado')) {
        $now = now();
        if ($request->estado == 'activas') {
            $query->where('fechaInicio', '<=', $now)
                  ->where('fechaFin', '>=', $now);
        } elseif ($request->estado == 'proximas') {
            $query->where('fechaInicio', '>', $now);
        }
    }
    
    $becas = $query->where('activo', true)
                ->orderBy('fechaPublicacion', 'desc')
                ->get();
    
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
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'requisitos' => 'required|array|min:1',
            'requisitos.*' => 'string',
            'documentos' => 'required|array|min:1',
            'documentos.*' => 'string',
            'promedioMinimo' => 'nullable|numeric|min:0|max:10',
            'sinReprobadas' => 'boolean',
            'condicionEspecial' => 'nullable|string',
            'fechaInicio' => 'required|date',
            'fechaFin' => 'required|date|after:fechaInicio',
            'monto' => 'required|numeric|min:0',
            'institucion' => 'required|string',
            'url' => 'nullable|url',
            'tipo' => 'required|in:beca,convocatoria,noticia'
        ]);

        
        $beca = new Beca();
        $beca->fill($validated);
        $beca->activo = true;
        $beca->autorID = Auth::user()->email;
        $beca->fechaPublicacion = Carbon::now();
        
        $beca->save();

        return redirect()->route('becas.index')->with('success', 'Beca/Noticia creada exitosamente!');
    }
}
