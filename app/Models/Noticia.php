<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Noticia extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'noticia';
    
    protected $fillable = [
        'autorID',
        'titulo',
        'contenido',
        'fechaCreacion',
        'fechaPublicacion',
        'activo',
        'imagenURL'
    ];
        public function user()
    {
        return $this->belongsTo(User::class, 'autorID', '_id');
    }
}