<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Reaction extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'reaccion';
    
    protected $fillable = [
        'publicacionID',
        'usuarioID',
        'tipo',
        'fecha'
    ];

    public function publicacion()
    {
        return $this->belongsTo(Publication::class, 'publicacionID', '_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuarioID', '_id');
    }
}