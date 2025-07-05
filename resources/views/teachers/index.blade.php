@extends('layouts.app')

@section('title', 'Teacher Management')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><i class="fas fa-chalkboard-teacher"></i> Teacher Management</h2>
        <a href="{{ route('teachers.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Add Teacher
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
                        <th>Name</th>
                        <th>Email</th>
                        <th>Group</th>
                        <th>Specialty</th>
                        <th>Subject</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $teacher->nombre }}</td> {{-- CAMBIO --}}
                            <td>{{ $teacher->correo }}</td> {{-- CAMBIO --}}
                            <td>{{ $teacher->grupo->nombreGrupo ?? '-' }}</td>                           
                            <td>{{ $teacher->specialty ?? '-' }}</td>
                            <td>{{ $teacher->subject ?? '-' }}</td>
                            <td>
                                <a href="{{ route('teachers.edit', $teacher->_id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('teachers.destroy', $teacher->_id) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this teacher?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No teachers registered.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
