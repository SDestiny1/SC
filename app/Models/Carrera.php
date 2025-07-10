<?php
// app/Models/Carrera.php
namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Carrera extends Model
{
    protected $collection = 'carrera'; // Nombre de tu colección en MongoDB
    protected $fillable = ['nombreCarrera', 'descripcion']; // Ajusta según tus campos

    public function grupos()
    {
        return $this->hasMany(Group::class, 'carreraID', '_id');
    }
    
}
