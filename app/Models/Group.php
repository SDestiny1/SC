<?php
// app/Models/Group.php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Group extends Model
{
    protected $collection = 'groups'; // o 'grupos' si así la llamaste
    protected $primaryKey = '_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        '_id', 'nombreGrupo', 'turno', 'nivel', 'semestre',
        'ciclo_id', 'carrera_id', 'activo'
    ];

public function carrera()
{
    return $this->belongsTo(Carrera::class, 'carrera_id', '_id');
}


}
