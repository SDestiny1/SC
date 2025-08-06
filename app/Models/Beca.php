<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Beca extends Model
{
    protected $collection = 'becaPrograma';
    protected $primaryKey = '_id';
    public $timestamps = false; // Deshabilitar timestamps automáticos
    protected $dates = ['fechaInicio', 'fechaFin', 'fechaPublicacion'];

    protected $fillable = [
        'titulo', 'descripcion', 'requisitos', 'documentos', 'condicionEspecial', 
        'fechaInicio', 'fechaFin', 'tipo', 'activo', 'autorID', 'fechaPublicacion',
        'monto', 'institucion', 'url'
    ];

    protected $casts = [
    'requisitos' => 'array',
    'documentos' => 'array',
    'activo' => 'boolean',
    'monto' => 'float'
];

// Asegurar que siempre devuelva arrays
public function getRequisitosAttribute($value)
{
    if (is_array($value)) {
        return $value;
    }
    
    if (is_string($value)) {
        return json_decode($value, true) ?: [];
    }
    
    return (array) $value;
}

public function getDocumentosAttribute($value)
{
    if (is_array($value)) {
        return $value;
    }
    
    if (is_string($value)) {
        return json_decode($value, true) ?: [];
    }
    
    return (array) $value;
}

// Asegurar que los arrays se guarden correctamente
public function setRequisitosAttribute($value)
{
    if (is_array($value)) {
        $this->attributes['requisitos'] = $value;
    } else {
        $this->attributes['requisitos'] = [];
    }
}

public function setDocumentosAttribute($value)
{
    if (is_array($value)) {
        $this->attributes['documentos'] = $value;
    } else {
        $this->attributes['documentos'] = [];
    }
}

// Asegurar que el autorID se guarde correctamente
public function setAutorIDAttribute($value)
{
    $this->attributes['autorID'] = (string) $value;
}

public function getAutorIDAttribute($value)
{
    return $value ?: '';
}
}