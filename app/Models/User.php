<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class User extends Eloquent implements AuthenticatableContract
{
    use Authenticatable;
    protected $connection = 'mongodb';
    protected $collection = 'usuarios';
    
    protected $fillable = [
        '_id',
        'nombre',
        'apellidoPaterno',
        'apellidoMaterno',
        'materiaID',
        'rol',
        'grupoID',
        'password',
        'activo',
        'fechaRegistro',
        'fechaNacimiento'
    ];

    protected $hidden = ['password'];

    public function grupo()
    {
        return $this->belongsTo(Group::class, 'grupoID', '_id')->with('carrera');
    }

 public function publicaciones()
    {
        return $this->hasMany(Publicacion::class, 'autorID', '_id');
    }
    
    // Relación con noticias
    public function noticias()
    {
        return $this->hasMany(Noticia::class, 'autorID', '_id');
    }

        public function materia()
    {
        return $this->belongsTo(Subject::class, 'materiaID', '_id');
    }
}

