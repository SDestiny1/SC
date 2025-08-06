<?php

namespace App\Http\Controllers;

use App\Models\SchoolCalendar;
use Illuminate\Http\Request;
use App\Models\Notice;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


class NoticiaController extends Controller
{
    public function create()
    {
        return view('noticias.create');
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'titulo' => 'required|string|max:255',
        'contenido' => 'required|string',
        'fechaPublicacion' => 'required|date|after_or_equal:now',
        'imagen' => 'nullable|image|max:2048'
    ]);

    $imagenURL = null;

    if ($request->hasFile('imagen') && $request->file('imagen')->isValid()) {
        try {
            // Subir imagen a Cloudinary usando la facade
            $uploadedFile = $request->file('imagen');
            $cloudinaryResponse = Cloudinary::upload($uploadedFile->getRealPath(), [
                'folder' => 'noticias', // Opcional: carpeta en Cloudinary
                'transformation' => [
                    'width' => 800,
                    'height' => 600,
                    'crop' => 'limit'
                ]
            ]);
            
            $imagenURL = $cloudinaryResponse->getSecurePath();
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error al subir la imagen: ' . $e->getMessage());
        }
    }

    try {
        Notice::create([
            'autorID' => (string) auth()->user()->_id,
            'titulo' => $validated['titulo'],
            'contenido' => $validated['contenido'],
            'fechaCreacion' => now(),
            'fechaPublicacion' => $validated['fechaPublicacion'],
            'activo' => true,
            'imagenLocalPath' => null, // Ya no guardamos localmente
            'imagenURL' => $imagenURL // Guardamos la URL de Cloudinary
        ]);

        return redirect()->route('noticias.index')->with('success', 'Noticia creada correctamente con imagen en Cloudinary.');
    } catch (\Exception $e) {
        return back()->with('error', 'Error al guardar noticia: ' . $e->getMessage());
    }
}


    public function index()
    {
        $noticias = Notice::orderBy('fechaPublicacion', 'desc')->get();
        return view('noticias.index', compact('noticias'));
    }

    public function mostrarNoticias()
{
    $noticias = \DB::connection('mongodb')->collection('noticia')
        ->orderBy('fechaCreacion', 'desc')
        ->get();

    return view('noticias.index', compact('noticias'));
}

public function edit($id)
    {
        $noticia = Notice::findOrFail($id);
        return view('noticias.edit', compact('noticia'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'required|string',
            'fechaPublicacion' => 'required|date',
            'imagen' => 'nullable|image|mimes:jpeg,png,gif|max:2048',
            'activo' => 'required|boolean',
            'remove_image' => 'nullable|boolean'
        ]);

        $noticia = Notice::findOrFail($id);
        $imagenURL = $noticia->imagenURL;

        // Manejo de la imagen
        if ($request->has('remove_image') && $request->remove_image) {
            // Eliminar imagen de Cloudinary si existe
            if ($imagenURL) {
                // Aquí deberías implementar la lógica para eliminar de Cloudinary
                // usando el public_id de la imagen
            }
            $imagenURL = null;
        } elseif ($request->hasFile('imagen') && $request->file('imagen')->isValid()) {
            // Subir nueva imagen a Cloudinary usando la facade
            $uploadedFile = $request->file('imagen');
            $cloudinaryResponse = Cloudinary::upload($uploadedFile->getRealPath(), [
                'folder' => 'noticias',
                'transformation' => [
                    'width' => 800,
                    'height' => 600,
                    'crop' => 'limit'
                ]
            ]);
            
            $imagenURL = $cloudinaryResponse->getSecurePath();
            
        }

        $noticia->update([
            'titulo' => $validated['titulo'],
            'contenido' => $validated['contenido'],
            'fechaPublicacion' => $validated['fechaPublicacion'],
            'activo' => $validated['activo'],
            'imagenURL' => $imagenURL
        ]);

        return redirect()->route('noticias.index')->with('success', 'Noticia actualizada correctamente.');
    }

    public function toggleStatus($id)
{
    try {
        $noticia = Notice::findOrFail($id);
        $noticia->activo = !$noticia->activo;
        $noticia->save();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $noticia->activo ? 'Noticia activada correctamente' : 'Noticia desactivada correctamente'
            ]);
        }

        $status = $noticia->activo ? 'activada' : 'desactivada';
        return back()->with('success', "Noticia {$status} correctamente.");
    } catch (\Exception $e) {
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado'
            ], 500);
        }

        return back()->with('error', 'Error al cambiar el estado de la noticia');
    }
}

    public function destroy($id)
    {
        try {
            $evento = SchoolCalendar::findOrFail($id);
            $evento->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Evento eliminado correctamente'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar el evento: ' . $e->getMessage()
            ], 500);
        }
    }
}
