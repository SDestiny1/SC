@extends('layouts.app')

@section('title', 'Detalles del Grupo')

@section('content')
<div class="container mt-4">
    <h2>Detalles del Grupo</h2>

    <div class="card p-3 mb-4">
        <strong>ID:</strong> {{ $grupo->_id }}<br>
        <strong>Nombre:</strong> {{ $grupo->nombreGrupo }}<br>
        <strong>Turno:</strong> {{ $grupo->turno }}<br>
        <strong>Nivel:</strong> {{ $grupo->nivel }}<br>
        <strong>Semestre:</strong> {{ $grupo->semestre }}<br>
        
        <td>{{ optional($grupo->carrera)->nombreCarrera ?? 'Sin carrera asignada' }}</td>


        <strong>Activo:</strong> {{ $grupo->activo ? 'Sí' : 'No' }}<br>
    </div>

    <a href="{{ route('groups.index') }}" class="btn btn-secondary">← Volver</a>
</div>
@endsection
