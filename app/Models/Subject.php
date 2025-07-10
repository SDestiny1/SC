<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

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
        return $this->belongsTo(Carrera::class, 'carreraID', '_id');
    }
    
    public function teachers()
    {
        return $this->hasMany(User::class, 'materiaID', '_id');
    }
}