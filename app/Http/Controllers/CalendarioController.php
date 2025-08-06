<?php
namespace App\Http\Controllers;

use App\Models\SchoolCalendar;
use Illuminate\Http\Request;
use MongoDB\BSON\UTCDateTime;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CalendarioImport;

class CalendarioController extends Controller
{
public function index()
{
    $calendario = SchoolCalendar::where('_id', 'calendario_2025A')->first();
    
    $eventos = [];
    
    if ($calendario && isset($calendario->eventos)) {
        foreach ($calendario->eventos as $index => $evento) {
            // Saltar eventos no activos
            if (!($evento['activo'] ?? true)) continue;

            // Manejar fechas nulas o inválidas
            $start = isset($evento['fechaInicio']) && $evento['fechaInicio'] instanceof \MongoDB\BSON\UTCDateTime
                ? $evento['fechaInicio']->toDateTime()->format('Y-m-d')
                : null;

            $end = isset($evento['fechaFin']) && $evento['fechaFin'] instanceof \MongoDB\BSON\UTCDateTime
                ? $evento['fechaFin']->toDateTime()->format('Y-m-d')
                : null;

            // Solo agregar eventos con fechas válidas
            if ($start) {
                $eventos[] = [
                    'id' => $index, // Agregar el índice como ID
                    'title' => $evento['nombreEvento'] ?? 'Sin nombre',
                    'start' => $start,
                    'end' => $end,
                    'color' => $this->colorPorTipo($evento['tipoEvento'] ?? 'evento'),
                    'descripcion' => $evento['descripcion'] ?? '',
                    'tipoEvento' => $evento['tipoEvento'] ?? 'evento',
                    'indice' => $index, // Agregar el índice para referencia
                ];
            }
        }
    }
    
    return view('calendario.index', compact('eventos'));
}

    public function store(Request $request)
    {
$request->validate([
    'nombre' => 'required|string|max:100',
    'tipo' => 'required|in:evento,periodo,inicio ciclo,evaluaciones,día feriado',
    'fecha' => [
        'required_if:tipo,evento,día feriado,inicio ciclo',
        'nullable',
        'date',
        'after_or_equal:today' // Asegura que la fecha sea hoy o futura
    ],
    'fecha_inicio' => [
        'required_if:tipo,periodo,evaluaciones',
        'nullable',
        'date',
    ],
    'fecha_fin' => [
        'required_if:tipo,periodo,evaluaciones',
        'nullable',
        'date',
        'after_or_equal:fecha_inicio'
    ],
    'descripcion' => 'nullable|string'
]);

        // Buscar el calendario existente
        $calendario = SchoolCalendar::where('_id', 'calendario_2025A')->first();
        
        if (!$calendario) {
            $calendario = new SchoolCalendar([
                '_id' => 'calendario_2025A',
                'año' => 2025,
                'eventos' => []
            ]);
        }

        // Preparar el nuevo evento
        $nuevoEvento = [
            'nombreEvento' => $request->nombre,
            'tipoEvento' => $request->tipo,
            'descripcion' => $request->descripcion,
            'fechaInicio' => new UTCDateTime(strtotime($request->tipo === 'periodo' || $request->tipo === 'evaluaciones' ? $request->fecha_inicio : $request->fecha) * 1000),
            'fechaFin' => ($request->tipo === 'periodo' || $request->tipo === 'evaluaciones') ? 
                          new UTCDateTime(strtotime($request->fecha_fin) * 1000) : 
                          new UTCDateTime(strtotime($request->tipo === 'evento' ? $request->fecha : $request->fecha) * 1000),
            'activo' => true
        ];

        // Agregar el nuevo evento al array de eventos
        $eventos = $calendario->eventos ?? [];
        $eventos[] = $nuevoEvento;

        // Actualizar o crear el calendario
        if ($calendario->exists) {
            $calendario->update(['eventos' => $eventos]);
        } else {
            $calendario->save();
        }

        return redirect()->route('calendario.index')->with('success', 'Evento agregado correctamente al calendario');
    }

    private function colorPorTipo($tipo)
    {
        return match ($tipo) {
            'inicio ciclo' => '#28a745', // verde
            'evaluaciones' => '#ffc107', // amarillo
            'día feriado' => '#dc3545',  // rojo
            'evento' => '#17a2b8',        // azul claro
            'periodo' => '#6610f2',       // morado
            default => '#007bff',         // azul por defecto
        };
    }
public function importar(Request $request)
{
    $request->validate(['archivo' => 'required|file|mimes:xlsx,xls,csv']);

    try {
        $import = new CalendarioImport();
        Excel::import($import, $request->file('archivo'));

        $message = sprintf(
            "Importación completada. %d registros importados, %d omitidos",
            $import->getImportedCount(),
            $import->getSkippedCount()
        );

        return back()->with('success', $message);

    } catch (\Exception $e) {
        Log::error("Error en importación: ".$e->getMessage());
        return back()->withErrors(['error' => 'Error al importar: '.$e->getMessage()]);
    }
}

public function update(Request $request, $year, $eventIndex)
{
    try {
        $request->validate([
            'nombre' => 'required|string|max:100',
            'tipoEvento' => 'required|in:evento,periodo,inicio ciclo,evaluaciones,día feriado',
            'fechaInicio' => 'required|date',
            'fechaFin' => 'nullable|date|after_or_equal:fechaInicio',
            'descripcion' => 'nullable|string'
        ]);

        $calendario = SchoolCalendar::where('_id', 'calendario_'.$year)->firstOrFail();
        
        if (!isset($calendario->eventos[$eventIndex])) {
            return response()->json([
                'success' => false,
                'message' => 'Evento no encontrado'
            ], 404);
        }

        // Obtener el array de eventos y modificarlo
        $eventos = $calendario->eventos ?? [];
        $eventos[$eventIndex] = [
            'nombreEvento' => $request->nombre,
            'tipoEvento' => $request->tipoEvento,
            'descripcion' => $request->descripcion,
            'fechaInicio' => new UTCDateTime(strtotime($request->fechaInicio) * 1000),
            'fechaFin' => new UTCDateTime(strtotime($request->fechaFin ?: $request->fechaInicio) * 1000),
            'activo' => true
        ];

        // Actualizar el calendario con el nuevo array
        $calendario->update(['eventos' => $eventos]);

        return response()->json([
            'success' => true,
            'message' => 'Evento actualizado correctamente'
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error de validación: ' . implode(', ', array_flatten($e->errors()))
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al actualizar: ' . $e->getMessage()
        ], 500);
    }
}

public function destroy($year, $eventIndex)
{
    try {
        $calendario = SchoolCalendar::where('_id', 'calendario_'.$year)->firstOrFail();
        
        if (!isset($calendario->eventos[$eventIndex])) {
            return response()->json([
                'success' => false,
                'message' => 'Evento no encontrado'
            ], 404);
        }
        
        // Elimina el evento del array
        $eventos = $calendario->eventos ?? [];
        array_splice($eventos, $eventIndex, 1);
        
        // Actualizar el calendario con el nuevo array
        $calendario->update(['eventos' => $eventos]);

        return response()->json([
            'success' => true,
            'message' => 'Evento eliminado correctamente'
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error al eliminar: ' . $e->getMessage()
        ], 500);
    }
}
}