<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Group extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'grupo';
    
    protected $fillable = [
        'nombre',
        'carrera',
        'semestre',
        'activo'
    ];
    
    // Relación con estudiantes
    public function students()
    {
        return $this->hasMany(User::class, 'grupoID', '_id');
    }
        public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carreraID', '_id');
    }
}


