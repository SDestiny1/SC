<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Publicacion extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'publicacion';
    
    protected $fillable = [
        'autorID',
        'contenido',
        'grupoID',
        'tipo',
        'fecha',
        'visibilidad',
        'imagenURL',
        'activo'
    ];
        public function user()
    {
        return $this->belongsTo(User::class, 'autorID', '_id');
    }

        public function carrera()
    {
        return $this->belongsTo(Comentario::class, 'publicacionID', '_id');
    }
}