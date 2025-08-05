<?php
// app/Models/ClassSchedule.php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class ClassSchedule extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'horarioClase';

    protected $fillable = [
        'grupoID', 'cicloID', 'diaSemana', 'clases', 'activo'
    ];

    public function materias()
    {
        return $this->hasMany(Subject::class, '_id', 'clases.materiaID');
    }
}
