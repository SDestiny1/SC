<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;
use App\Models\Publicacion;
use Carbon\Carbon;
use MongoDB\BSON\UTCDateTime;
use App\Models\Comment;
use MongoDB\BSON\ObjectId;
use App\Models\Post;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;


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
        $post = Publication::findOrFail($id);
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



public function show($id)
{
    $post = Publication::findOrFail($id);

    // Convertir fecha publicación a string ISO 8601 o formato legible
    $postArray = $post->toArray();

    if ($post->fecha instanceof \MongoDB\BSON\UTCDateTime) {
        $postArray['fecha'] = $post->fecha->toDateTime()->format('c'); // ISO 8601
    } else {
        // Intentar formatear de forma segura
        try {
            $dt = Carbon::parse($post->fecha);
            $postArray['fecha'] = $dt->format('c');
        } catch (\Exception $e) {
            $postArray['fecha'] = null;
        }
    }

    // Obtener comentarios
$comentarios = \DB::connection('mongodb')
    ->collection('comentario')
    ->where('publicacionID', new ObjectId($post->_id))
    ->orderBy('fecha', 'desc')
    ->get()
    ->map(function($comentario) {
        // Formatear fecha
        if (isset($comentario['fecha']) && $comentario['fecha'] instanceof \MongoDB\BSON\UTCDateTime) {
            $comentario['fecha'] = $comentario['fecha']->toDateTime()->format('c');
        }

        // Buscar nombre del usuario
        $usuario = \DB::connection('mongodb')
            ->collection('usuarios')
            ->where('_id', $comentario['usuarioID']) // "_id" es el correo
            ->first();

        if ($usuario) {
            $comentario['autorNombre'] = trim(
                ($usuario['nombre'] ?? '') . ' ' .
                ($usuario['apellidoPaterno'] ?? '') . ' ' .
                ($usuario['apellidoMaterno'] ?? '')
            );
        } else {
            $comentario['autorNombre'] = 'Anónimo';
        }

        return $comentario;
    });


    // Likes (opcional)
    $likes = \DB::connection('mongodb')
        ->collection('reaccion')
        ->where('publicacionID', new ObjectId($post->_id))
        ->where('tipo', 'like')
        ->get();

    if (request()->wantsJson()) {
        return response()->json([
            'post' => $postArray,
            'comentarios' => $comentarios,
            'likes' => $likes,
        ]);
    }

    return view('posts.show', compact('post', 'comentarios', 'likes'));
}




}
