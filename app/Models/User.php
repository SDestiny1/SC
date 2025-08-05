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
    protected $dates = ['fechaRegistro', 'created_at', 'updated_at'];


public function grupo()
{
    return $this->belongsTo(Group::class, 'grupoID', '_id');
}

 public function publicaciones()
    {
        return $this->hasMany(Publication::class, 'autorID', '_id');
    }
    
    public function noticias()
    {
        return $this->hasMany(Notice::class, 'autorID', '_id');
    }

        public function materia()
{
    return $this->hasMany(Subject::class, 'docenteID', '_id');
}

    public function materias()
{
    return ClassSchedule::where('clases.docenteID', $this->_id)
        ->get()
        ->flatMap(function ($schedule) {
            return collect($schedule->clases)
                ->where('docenteID', $this->_id)
                ->pluck('materiaID');
        })
        ->unique()
        ->map(function ($materiaID) {
            return Subject::where('_id', $materiaID)->first();
        })
        ->filter();
}

}

