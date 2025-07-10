<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Publicacion;
use App\Models\Noticia;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Inicializar variables
        $data = [
            'activeUsersCount' => 0,
            'studentPublicationsCount' => 0,
            'teacherPublicationsCount' => 0,
            'activeNewsCount' => 0,
            'recentActivities' => [] // Nuevo array para actividades recientes
        ];

        try {
            // Contar usuarios activos
            $data['activeUsersCount'] = User::where('activo', true)->count();
            
            // Obtener IDs de alumnos activos
            $alumnosIds = User::where('rol', 'alumno')
                              ->where('activo', true)
                              ->pluck('_id')
                              ->toArray();
            
            // Contar publicaciones de alumnos activos
            $data['studentPublicationsCount'] = Publicacion::whereIn('autorID', $alumnosIds)
                                                  ->where('activo', true)
                                                  ->count();
            
            // Obtener IDs de maestros activos
            $maestrosIds = User::where('rol', 'maestro')
                               ->where('activo', true)
                               ->pluck('_id')
                               ->toArray();
            
            // Contar publicaciones de maestros activos
            $data['teacherPublicationsCount'] = Publicacion::whereIn('autorID', $maestrosIds)
                                                  ->where('activo', true)
                                                  ->count();
            
            // Contar noticias activas
            $data['activeNewsCount'] = Noticia::where('activo', true)->count();
            
            $recentPublications = Publicacion::with('user')
                ->where('activo', true)
                ->orderBy('fecha', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($publicacion) {
                    return [
                        'type' => 'publicacion',
                        'user' => $publicacion->user->nombre ?? 'Usuario desconocido',
                        'content' => $publicacion->contenido,
                        'date' => $this->parseDate($publicacion->fecha),
                        'status' => 'Activo'
                    ];
                });
            
            $recentNews = Noticia::with('user')
                ->where('activo', true)
                ->orderBy('fechaPublicacion', 'desc')
                ->limit(5)
                ->get()
                ->map(function ($noticia) {
                    return [
                        'type' => 'noticia',
                        'user' => $noticia->user->nombre ?? 'Usuario desconocido',
                        'content' => $noticia->titulo,
                        'date' => $this->parseDate($noticia->fechaPublicacion),
                        'status' => 'Activo'
                    ];
                });
            
            // Combinar y ordenar actividades
            $data['recentActivities'] = $recentPublications->merge($recentNews)
                ->sortByDesc('date')
                ->take(5);

        } catch (\Exception $e) {
            Log::error('Error en DashboardController: ' . $e->getMessage());
        }

        return view('dashboard', $data);
    }

    /**
     * Convierte diferentes formatos de fecha a instancias Carbon
     */
    private function parseDate($dateValue)
    {
        try {
            // Si es un timestamp en milisegundos
            if (is_numeric($dateValue)) {
                return Carbon::createFromTimestampMs($dateValue);
            }
            
            // Si es una cadena de fecha
            if (is_string($dateValue)) {
                return Carbon::parse($dateValue);
            }
            
            // Si ya es un objeto Carbon o DateTime
            if ($dateValue instanceof \Carbon\Carbon) {
                return $dateValue;
            }
            
            // Si es un objeto UTCDateTime de MongoDB
            if ($dateValue instanceof \MongoDB\BSON\UTCDateTime) {
                return Carbon::createFromTimestampMs($dateValue->toDateTime()->getTimestamp() * 1000);
            }
            
            return now();
        } catch (\Exception $e) {
            Log::error('Error al parsear fecha: ' . $e->getMessage());
            return now();
        }
    }
}