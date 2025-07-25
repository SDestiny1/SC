@extends('layouts.app')
@section('title', 'Gestión de Alumnos')
@section('content')
    <!-- Contenido principal -->
<main class="main-content">
    <section class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
                <img src="{{ $student->fotoPerfil ?? 'https://randomuser.me/api/portraits/men/32.jpg' }}" alt="Foto de perfil" class="profile-avatar">
                <div class="profile-basic">
                    <h2>{{ $student->nombre }} {{ $student->apellidoPaterno }} {{ $student->apellidoMaterno }}</h2>
                    <p><i class="fas fa-envelope"></i> {{ $student->_id }}</p>
                    <p><i class="fas fa-graduation-cap"></i> {{ $student->grupo->carrera->nombreCarrera ?? 'Sin carrera' }}</p>
                    <p><i class="fas fa-layer-group"></i> {{ $student->grupo->semestre ?? 'Sin semestre' }}° Semestre</p>
                </div>
            </div>

            <div class="profile-details">
                <h3>Información General</h3>
                <ul>
                    <li><strong>Matrícula:</strong> {{ $student->matricula ?? substr($student->_id, 0, 8) }}</li>
                    <li><strong>Fecha de nacimiento:</strong> {{ \Carbon\Carbon::instance($student->fechaNacimiento->toDateTime())->format('d/m/Y') }} </li>
                    <li><strong>Grupo:</strong> {{ $student->grupo->nombreGrupo ?? 'No asignado' }}</li>
                    <li><strong>Estatus:</strong> 
                        <span class="status-badge status-{{ $student->activo ? 'active' : 'inactive' }}">
                            {{ $student->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </li>
                    <li><strong>Fecha de registro:</strong> {{ \Carbon\Carbon::parse($student->fechaRegistro)->format('d/m/Y') }}</li>
                </ul>
            </div>

            <div class="profile-actions">
                <a href="{{ route('students.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver a la lista
                </a>
            </div>
        </div>
    </section>
</main>

<style>
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
        text-align: right;
    }

    .btn-secondary {
        background: #ccc;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        text-decoration: none;
        color: #000;
        font-weight: bold;
        transition: background 0.3s ease;
    }

    .btn-secondary:hover {
        background: #bbb;
    }
</style>
