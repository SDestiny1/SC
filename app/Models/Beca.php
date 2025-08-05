<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;

class Beca extends Model
{
    protected $collection = 'becaPrograma';
    protected $primaryKey = '_id';
    protected $dates = ['fechaInicio', 'fechaFin', 'fechaPublicacion'];

    protected $fillable = [
        'titulo', 'descripcion', 'requisitos', 'promedioMinimo', 
        'sinReprobadas', 'documentos', 'condicionEspecial', 'fechaInicio',
        'fechaFin', 'tipo', 'activo', 'autorID', 'fechaPublicacion',
        'monto', 'institucion', 'url'
    ];

    protected $casts = [
    'requisitos' => 'array',
    'documentos' => 'array',
    'promedioMinimo' => 'float',
    'sinReprobadas' => 'boolean',
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
}