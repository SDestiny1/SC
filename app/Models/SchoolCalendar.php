<?php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CalendarioImport;


class SchoolCalendar extends Model
{
    protected $collection = 'calendarioEscolar';
    protected $connection = 'mongodb';
    protected $guarded = [];

    
public function importar(Request $request)
{
    $request->validate(['archivo' => 'required|file|mimes:xlsx,xls']);

    try {
        Excel::import(new CalendarioImport, $request->file('archivo'));
        return back()->with('success', 'Eventos importados correctamente.');
    } catch (\Exception $e) {
        return back()->withErrors([
            'error' => 'Error al importar: ' . $e->getMessage() . 
                      ' (Línea: ' . $e->getLine() . ')'
        ]);
    }
}
}

