@extends('layouts.app')

@section('title', 'Perfil del Maestro')

@section('content')
<main class="main-content">
    <section class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
                <img src="{{ $teacher->fotoPerfil ?? 'https://randomuser.me/api/portraits/men/32.jpg' }}" alt="Foto de perfil" class="profile-avatar">
                <div class="profile-basic">
                    <h2>{{ $teacher->nombre }} {{ $teacher->apellidoPaterno }} {{ $teacher->apellidoMaterno }}</h2>
                    <p><i class="fas fa-envelope"></i> {{ $teacher->_id }}</p>
                    <p><i class="fas fa-book"></i> {{ $teacher->materia?->nombre ?? 'Sin materia asignada' }}</p>
                    <p><i class="fas fa-building"></i> {{ $teacher->grupo?->carrera?->nombreCarrera ?? 'Sin departamento asignado' }}</p>
                </div>
            </div>

            <div class="profile-details">
                <h3>Información General</h3>
                <ul>
                    <li><strong>Especialidad:</strong> {{ $teacher->specialty ?? 'No especificada' }}</li>
                    <li><strong>Departamento:</strong> {{ $teacher->grupo?->carrera?->nombreCarrera ?? 'No asignado' }}</li>
                    <li><strong>Grupo asignado:</strong> {{ $teacher->grupo?->nombreGrupo ?? 'No asignado' }}</li>
                    <li><strong>Estatus:</strong> 
                        <span class="status-badge status-{{ $teacher->activo ? 'active' : 'inactive' }}">
                            {{ $teacher->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </li>
<li><strong>Fecha de registro:</strong> 
    @if(is_numeric($teacher->fechaRegistro))
        {{ \Carbon\Carbon::createFromTimestampMs($teacher->fechaRegistro)->format('d/m/Y') }}
    @else
        {{ $teacher->fechaRegistro ? \Carbon\Carbon::parse($teacher->fechaRegistro)->format('d/m/Y') : 'N/A' }}
    @endif
</li>
                </ul>
            </div>

            <div class="profile-actions">
                <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a la lista
                </a>
                @can('edit teachers')
                <a href="{{ route('teachers.edit', $teacher->_id) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Editar Perfil
                </a>
                @endcan
            </div>
        </div>
    </section>
</main>

<style>
    /* Estilos similares a los del alumno pero adaptados */
    .profile-container {
        display: flex;
        justify-content: center;
        padding: 2rem;
    }

    .profile-card {
        background: #fff;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 800px;
    }

    .profile-header {
        display: flex;
        gap: 1.5rem;
        align-items: center;
        border-bottom: 1px solid #eee;
        padding-bottom: 1rem;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ccc;
    }

    .profile-basic h2 {
        margin: 0;
        font-size: 1.8rem;
    }

    .profile-basic p {
        margin: 0.2rem 0;
        color: #555;
    }

    .profile-details {
        margin-top: 2rem;
    }

    .profile-details h3 {
        margin-bottom: 1rem;
        color: #333;
    }

    .profile-details ul {
        list-style: none;
        padding: 0;
    }

    .profile-details li {
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .status-badge {
        padding: 0.2rem 0.6rem;
        border-radius: 0.5rem;
        font-weight: bold;
        color: #fff;
    }

    .status-active {
        background-color: #28a745;
    }

    .status-inactive {
        background-color: #dc3545;
    }

    .profile-actions {
        margin-top: 2rem;
        display: flex;
        justify-content: space-between;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-weight: bold;
        transition: background 0.3s ease;
    }

    .btn-secondary {
        background: #ccc;
        color: #000;
    }

    .btn-secondary:hover {
        background: #bbb;
    }

    .btn-primary {
        background: #007bff;
        color: #fff;
    }

    .btn-primary:hover {
        background: #0069d9;
    }
</style>
