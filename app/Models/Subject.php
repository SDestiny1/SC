<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use App\Models\Career;

class Subject extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'materias';
    
    protected $fillable = [
        '_id',
        'nombre',
        'carreraID',
        'cicloID',
        'horasSemana',
        'activo'
    ];
    
    public function carrera()
    {
        return $this->belongsTo(Career::class, 'carreraID', '_id');
    }
    
    public function teachers()
    {
        return $this->hasMany(User::class, 'materiaID', '_id');
    }

    // En Subject.php
public function docente()
{
    return $this->belongsTo(User::class, 'docenteID', '_id');
}
}