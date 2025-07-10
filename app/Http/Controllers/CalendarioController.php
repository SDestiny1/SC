<?php
namespace App\Http\Controllers;

use App\Models\CalendarioEscolar;

class CalendarioController extends Controller
{
    public function index()
{
    $calendario = CalendarioEscolar::where('año', 2025)->first();

    $eventos = []; // <-- Siempre definirla, aunque no haya datos

    if ($calendario && isset($calendario->eventos)) {
        foreach ($calendario->eventos as $evento) {
            $eventos[] = [
                'title' => $evento['nombreEvento'],
'start' => $evento['fechaInicio']->toDateTime()->format('Y-m-d'),
'end' => $evento['fechaFin']->toDateTime()->format('Y-m-d'),

                'color' => $this->colorPorTipo($evento['tipoEvento']),
            ];
        }
    }
    return view('calendario.index', compact('eventos'));
}


    // Método para asignar colores según tipoEvento
    private function colorPorTipo($tipo)
    {
        return match ($tipo) {
            'inicio ciclo' => '#28a745', // verdea
            'evaluaciones' => '#ffc107', // amarillo
            'día feriado' => '#dc3545',  // rojo
            default => '#007bff',         // azul por defecto
        };
    }
}
