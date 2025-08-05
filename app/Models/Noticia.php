<?php
class Noticia extends \Jenssegers\Mongodb\Eloquent\Model
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
}
