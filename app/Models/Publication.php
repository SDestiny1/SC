<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Publication extends Model
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
    return $this->belongsTo(User::class, 'autorID', '_id'); // Si usas MongoDB con IDs personalizados
}

public function comentarios()
{
    return $this->hasMany(Comment::class, 'publicacionID', '_id');
}

public function reacciones()
{
    return $this->hasMany(Reaction::class, 'publicacionID', '_id');
}

public function likes()
{
    return $this->hasMany(Reaction::class, 'publicacionID', '_id')->where('tipo', 'like');
}


        public function carrera()
    {
        return $this->belongsTo(Comment::class, 'publicacionID', '_id');
    }
}