<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Publicacion;
use Carbon\Carbon;
use MongoDB\BSON\UTCDateTime;
use App\Models\Comentario;
use MongoDB\BSON\ObjectId;

class PostController extends Controller
{
    public function index()
    {
        $posts = Publicacion::with('user')
                      ->orderBy('fecha', 'desc')
                      ->paginate(10);
        // Convertimos el campo fecha para cada post
                        foreach ($posts as $post) {
                    // Convertir fecha si es tipo UTCDateTime
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
                    $post->comentarios_count = Comentario::where('publicacionID', new ObjectId($post->_id))->count();
                }
        return view('posts.index', compact('posts'));
    }

    public function destroy($id)
    {
        $post = Publicacion::findOrFail($id);
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Publicación eliminada correctamente.');
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
}
