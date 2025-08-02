<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;
use App\Models\Publicacion;
use Carbon\Carbon;
use MongoDB\BSON\UTCDateTime;
use App\Models\Comment;
use MongoDB\BSON\ObjectId;
use Illuminate\Support\Facades\Storage;



class PostController extends Controller
{
public function index()
{
    $posts = Publication::orderBy('fecha', 'desc')->get();

    foreach ($posts as $post) {
        // Fecha
        if ($post->fecha instanceof UTCDateTime) {
            $post->fecha_carbon = Carbon::createFromTimestampMs($post->fecha->toDateTime()->format('Uv'));
        } else {
            try {
                $timestamp = is_numeric($post->fecha) ? intval($post->fecha) : strtotime($post->fecha);
                $post->fecha_carbon = Carbon::createFromTimestampMs($timestamp);
            } catch (\Exception $e) {
                $post->fecha_carbon = Carbon::now();
            }
        }

        // Contar comentarios por publicacionID
        $post->comentarios_count = \DB::connection('mongodb')
            ->collection('comentario')
            ->where('publicacionID', new ObjectId($post->_id))
            ->count();

        // Contar likes desde colección 'reaccion' con tipo = 'like'
        $post->likes_count = \DB::connection('mongodb')
            ->collection('reaccion')
            ->where('publicacionID', new ObjectId($post->_id))
            ->where('tipo', 'like')
            ->count();
    }

    return view('posts.index', compact('posts'));
}


    public function destroy($id)
    {
        $post = Publication::findOrFail($id);
        $post->delete();
        return back()->with('success', 'Estado cambiado correctamente');
    }

    public function manage()
    {
        try {
            \Log::info('PostController@manage - Iniciando método');

            $posts = collect([
                (object) [
                    '_id' => '1',
                    'tipo' => 'publicacion',
                    'activo' => true,
                    'autorID' => 'Juan Pérez',
                    'contenido' => 'Esta es una publicación de prueba',
                    'grupoID' => 'Estudiantes',
                    'visibilidad' => 'Pública',
                    'fecha' => now(),
                    'imagenURL' => null
                ],
                (object) [
                    '_id' => '2',
                    'tipo' => 'pregunta',
                    'activo' => false,
                    'autorID' => 'María García',
                    'contenido' => '¿Cuándo son los exámenes?',
                    'grupoID' => 'Consultas',
                    'visibilidad' => 'Grupo',
                    'fecha' => now()->subHours(2),
                    'imagenURL' => null
                ]
            ]);

            \Log::info('PostController@manage - Posts obtenidos: ' . $posts->count());

            return view('posts.manage-debug', compact('posts'));

        } catch (\Exception $e) {
            \Log::error('PostController@manage - Error: ' . $e->getMessage());
            \Log::error('PostController@manage - Trace: ' . $e->getTraceAsString());

            return view('posts.manage-debug', [
                'posts' => collect([]),
                'error' => $e->getMessage()
            ]);
        }
    }
public function toggleStatus($id)
{
    try {
        $post = Post::findOrFail($id);
        $newStatus = !$post->activo;
        $post->update(['activo' => $newStatus]);
        
        $message = $newStatus 
            ? 'La publicación ha sido activada' 
            : 'La publicación ha sido desactivada';
            
        return back()->with('success', $message);
    } catch (\Exception $e) {
        return back()->with('error', 'Error al cambiar el estado: ' . $e->getMessage());
    }
}
public function create()
{
    return view('posts.create');
}
public function store(Request $request)
{
    $validated = $request->validate([
        'titulo' => 'required|string|max:255',
        'contenido' => 'required|string',
        'fechaPublicacion' => 'required|date|after_or_equal:fechaCreacion',
        'activo' => 'required|boolean',
        'imagen' => 'nullable|image|max:2048' // máximo 2MB
    ]);

    // Procesar imagen si se subió
    $imagenURL = null;
    if ($request->hasFile('imagen')) {
$path = $request->file('imagen')->store('public/noticias');
        $imagenURL = Storage::url($path); // genera una URL como /storage/noticias/archivo.jpg
    }

    // Asignar el documento de forma segura
    $documento = [
        'autorID' => (string) auth()->user()->_id,
        'titulo' => $validated['titulo'],
        'contenido' => $validated['contenido'],
        'fechaCreacion' => now()->toISOString(),
        'fechaPublicacion' => $validated['fechaPublicacion'],
        'activo' => (bool) $validated['activo'],
        'imagenURL' => $imagenURL
    ];

    // Insertar en la colección correcta
    \DB::connection('mongodb')->collection('noticia')->insert($documento);

    return redirect()->route('posts.index')->with('success', 'Noticia creada correctamente.');
}

public function mostrarNoticias()
{
    $noticias = \DB::connection('mongodb')->collection('noticia')
        ->orderBy('fechaCreacion', 'desc')
        ->get();

    return view('noticias.index', compact('noticias'));
}

public function show($id)
{
    // Buscar la publicación
    $post = Publication::findOrFail($id);

    // Formatear fecha
if ($post->fecha instanceof UTCDateTime) {
    $post->fecha_carbon = Carbon::createFromTimestampMs($post->fecha->toDateTime()->format('Uv'));
} else {
    try {
        if (is_numeric($post->fecha)) {
            $timestamp = (int) $post->fecha;
            // Si el timestamp es mayor a 10^12 asumimos milisegundos
            if ($timestamp > 1000000000000) {
                $timestamp = (int) ($timestamp / 1000);
            }
        } else {
            $timestamp = strtotime($post->fecha);
        }
        $post->fecha_carbon = Carbon::createFromTimestamp($timestamp);
    } catch (\Exception $e) {
        \Log::error('Error parseando fecha: ' . $e->getMessage());
        $post->fecha_carbon = Carbon::now();
    }
}


    // Obtener comentarios relacionados
    $comentarios = \DB::connection('mongodb')
        ->collection('comentario')
        ->where('publicacionID', new ObjectId($post->_id))
        ->orderBy('fecha', 'desc')
        ->get();

    // Obtener usuarios que dieron like (reacciones con tipo 'like')
    $likes = \DB::connection('mongodb')
        ->collection('reaccion')
        ->where('publicacionID', new ObjectId($post->_id))
        ->where('tipo', 'like')
        ->get();

    return view('posts.show', compact('post', 'comentarios', 'likes'));
}

}
