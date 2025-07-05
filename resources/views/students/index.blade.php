@extends('layouts.app')

@section('title', 'Student Management')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-user-graduate"></i> Student Management</h2>
        <a href="{{ route('students.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> New Student
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-striped table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th>Edad</th>
                        <th>Grupo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php $filteredStudents = $students->where('rol', 'alumno'); @endphp
                    @forelse($filteredStudents as $student)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $student->nombre }}</td>
                            <td>{{ $student->correo }}</td>
                            <td>{{ $student->edad ?? '-' }}</td>
                            <td>{{ $student->grupo->nombreGrupo ?? '-' }}</td>
                            <td>
                                <a href="{{ route('students.edit', $student->_id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Editar
                                </a>
                                <form action="{{ route('students.destroy', $student->_id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Estás seguro de eliminar este estudiante?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">No hay estudiantes registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
