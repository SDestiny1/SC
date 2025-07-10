@extends('layouts.app')

@section('title', 'Lista de Grupos')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Gestión de Grupos</h2>


    @if ($grupos->isEmpty())
        <p>No hay grupos registrados.</p>
    @else
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Turno</th>
                    <th>Nivel</th>
                    <th>Semestre</th>
                    <th>Carrera</th>
                    <th>Activo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($grupos as $grupo)
                    <tr>
                        <td>{{ $grupo->_id }}</td>
                        <td>{{ $grupo->nombreGrupo }}</td>
                        <td>{{ $grupo->turno }}</td>
                        <td>{{ $grupo->nivel }}</td>
                        <td>{{ $grupo->semestre }}</td>
                        <td>{{ optional($grupo->carrera)->nombreCarrera ?? 'Sin carrera asignada' }}</td>

                        <td>
                            <span class="badge bg-{{ $grupo->activo ? 'success' : 'secondary' }}">
                                {{ $grupo->activo ? 'Sí' : 'No' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('groups.show', $grupo->_id) }}" class="btn btn-info btn-sm">👁️ Ver</a>
                            <a href="{{ route('groups.edit', $grupo->_id) }}" class="btn btn-warning btn-sm">✏️ Editar</a>
                            <form action="{{ route('groups.destroy', $grupo->_id) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de eliminar este grupo?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm">🗑️ Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
