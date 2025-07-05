@extends('layouts.app')

@section('title', 'Editar Alumno')

@section('content')
<div class="container mt-4">
    <h2>Editar Alumno</h2>

    <form action="{{ route('students.update', $student->_id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control"
                value="{{ old('nombre', $student->nombre) }}" required>
        </div>

        <div class="mb-3">
            <label for="correo" class="form-label">Correo Electrónico</label>
            <input type="email" name="correo" id="correo" class="form-control"
                value="{{ old('correo', $student->correo) }}" required>
        </div>

        <div class="mb-3">
            <label for="age" class="form-label">Edad</label>
            <input type="number" name="age" id="age" class="form-control"
                value="{{ old('age', $student->age) }}" required>
        </div>

        <div class="mb-3">
    <label for="grupo_id" class="form-label">Grupo</label>
    <select name="grupo_id" id="grupo_id" class="form-control" required>
        <option value="">Selecciona un grupo</option>
        @foreach ($groups as $group)
            <option value="{{ $group->_id }}"
                {{ old('grupo_id', $student->grupo_id) == $group->_id ? 'selected' : '' }}>
                {{ $group->nombreGrupo }}
            </option>
        @endforeach
    </select>
</div>

        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
