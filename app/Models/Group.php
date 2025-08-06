<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use App\Models\Career;

class Group extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'grupo';
    
    protected $fillable = [
        'nombre',
        'carreraID',
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
        return $this->belongsTo(Career::class, 'carreraID', '_id');
    }
}


