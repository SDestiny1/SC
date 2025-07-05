<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class User extends Eloquent implements AuthenticatableContract
{
    use Authenticatable;

    protected $collection = 'users'; // nombre de la colección en MongoDB
    protected $primaryKey = '_id'; // clave primaria personalizada

    protected $fillable = [
        'nombre', 'correo', 'password', 'rol', 'grupo_id', 'activo', 'fechaRegistro',
    ];

    protected $hidden = ['password'];

    public function grupo()
{
    return $this->belongsTo(Group::class, 'grupo_id', '_id');
}
}
