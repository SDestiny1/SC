<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Comentario extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'comentario';

    protected $fillable = [
        'publicacionID',
        'usuarioID',
        'contenido',
        'fecha'
    ];
}
