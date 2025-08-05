<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Notice extends Model
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
        'imagenLocalPath',
        'imagenURL'
    ];

    protected $casts = [
        'fechaCreacion' => 'datetime',
        'fechaPublicacion' => 'datetime',
        'activo' => 'boolean'
    ];

public function user()
{
    return $this->belongsTo(User::class, 'autorID', '_id');
}

}

