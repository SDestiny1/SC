@extends('layouts.app')

@section('title', 'Gestión de Docentes')

@section('content')
    <!-- Contenido principal -->
    <main class="main-content">
        <header class="header">
            <h1><i class="fas fa-chalkboard-teacher"></i> Gestión de Personal Académico</h1>
            <div class="user-info">
                <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Usuario">
                <span>
                {{ trim(
                    (Auth::user()->nombre ?? '') . ' ' .
                    (Auth::user()->apellidoPaterno ?? '') . ' ' .
                    (Auth::user()->apellidoMaterno ?? '')
                ) }}
            </span>
            </div>
        </header>

        <!-- Estadísticas rápidas -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-label"><i class="fas fa-user-tie"></i> Total de Docentes</div>
                <div class="stat-value">{{ $totalTeachers }}</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-label"><i class="fas fa-book"></i> Materias Impartidas</div>
                <div class="stat-value">{{ $totalSubjects }}</div>
            </div>
            
            <div class="stat-card">
                <div class="stat-label"><i class="fas fa-users"></i> Grupos Asignados</div>
                <div class="stat-value">{{ $totalGroups }}</div>
            </div>
        </div>

        <!-- Barra de herramientas -->
        <div class="toolbar">
            <form action="{{ route('teachers.index') }}" method="GET" class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Buscar docentes por nombre o materia..." value="{{ request('search') }}">
            </form>
            <div class="action-buttons">
                <a href="{{ route('teachers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    <span>Nuevo Docente</span>
                </a>
                <button class="btn btn-secondary">
                    <i class="fas fa-file-export"></i>
                    <span>Exportar Datos</span>
                </button>
                <button class="btn btn-outline" id="toggleFilters">
                    <i class="fas fa-filter"></i>
                    <span>Filtros</span>
                </button>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filters" id="filtersSection" style="display: none;">
            <form action="{{ route('teachers.index') }}" method="GET">
                <div class="filter-group">
                    <label for="department">Departamento</label>
                    <select id="department" name="department" class="form-control">
                        <option value="">Todos los departamentos</option>
                        @foreach($departments as $id => $name)
                            <option value="{{ $id }}" {{ request('department') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="status">Estatus</label>
                    <select id="status" name="status" class="form-control">
                        <option value="">Todos los estatus</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Activo</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                    </select>
                </div>
                
                <div class="filter-group">
                    <label for="subject">Materia</label>
                    <select id="subject" name="subject" class="form-control">
                        <option value="">Todas las materias</option>
                        @foreach($subjects as $id => $name)
                            <option value="{{ $id }}" {{ request('subject') == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                    <a href="{{ route('teachers.index') }}" class="btn btn-outline">Limpiar</a>
                </div>
            </form>
        </div>

        <!-- Grid de docentes -->
        <div class="professor-grid">
            @forelse($teachers as $teacher)
            <div class="professor-card">
                <div class="professor-header">
                    <img src="{{ $teacher->fotoPerfil ?? 'https://randomuser.me/api/portraits/men/'.rand(1,99).'.jpg' }}" 
                         alt="Docente" class="professor-avatar">
                    <div class="professor-info">
                        <div class="professor-name">
                            {{ $teacher->nombre }} {{ $teacher->apellidoPaterno }} {{ $teacher->apellidoMaterno }}
                        </div>
                        <div class="professor-title">Profesor</div>
                        <div class="professor-id">ID: {{ substr($teacher->_id, 0, 8) }}</div>
                    </div>
                </div>
                <div class="professor-details">
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Departamento</div>
                            <div class="detail-value">
                                {{ $teacher->grupo->carrera->nombreCarrera ?? 'Sin departamento' }}
                            </div>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Materia</div>
                            <div class="detail-value">
                                {{ $teacher->materia->nombre ?? 'Sin materia asignada' }}
                            </div>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Correo</div>
                            <div class="detail-value">{{ $teacher->_id }}</div>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Grupo</div>
                            <div class="detail-value">
                                {{ $teacher->grupo->nombreGrupo ?? 'Sin grupo asignado' }}
                            </div>
                        </div>
                    </div>
                    <div class="detail-item">
                        <div class="detail-icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <div class="detail-content">
                            <div class="detail-label">Estatus</div>
                            <div class="professor-status status-{{ $teacher->activo ? 'active' : 'inactive' }}">
                                {{ $teacher->activo ? 'Activo' : 'Inactivo' }}
                            </div>
                        </div>
                    </div>
                </div>
<div class="professor-actions">
    <a href="{{ route('teachers.show', $teacher->_id) }}" class="action-btn">
        <i class="fas fa-eye"></i> Ver
    </a>                    
    <a href="mailto:{{ $teacher->_id }}" class="action-btn">
        <i class="fas fa-envelope"></i> Contactar
    </a>
    <form action="{{ route('teachers.destroy', $teacher->_id) }}" method="POST" class="status-form">
        @csrf
        @method('DELETE')
        <button type="submit" class="action-btn {{ $teacher->activo ? 'status-inactive' : 'status-active' }}">
            <i class="fas {{ $teacher->activo ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
            {{ $teacher->activo ? 'Desactivar' : 'Activar' }}
        </button>
    </form>
</div>
            </div>
            @empty
            <div class="no-results">
                <i class="fas fa-user-slash"></i>
                <p>No se encontraron docentes</p>
            </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($teachers->hasPages())
        <div class="pagination">
            {{ $teachers->appends(request()->query())->links() }}
        </div>
        @endif
    </main>

    
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar/ocultar filtros
    const toggleFilters = document.getElementById('toggleFilters');
    const filtersSection = document.getElementById('filtersSection');
    
    toggleFilters.addEventListener('click', function() {
        filtersSection.style.display = filtersSection.style.display === 'none' ? 'block' : 'none';
    });
    
    // Configuración de SweetAlert para cambiar estatus de docentes
    const statusForms = document.querySelectorAll('.status-form');
    statusForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const professorCard = form.closest('.professor-card');
            const professorName = professorCard.querySelector('.professor-name').textContent;
            const currentStatus = professorCard.querySelector('.professor-status').textContent.trim();
            const willBeActive = form.querySelector('button').classList.contains('status-active');
            const action = willBeActive ? 'reactivar' : 'desactivar';
            const statusText = willBeActive ? 'Activo' : 'Inactivo';
            const icon = willBeActive ? 'success' : 'warning';
            
            Swal.fire({
                title: `¿${action.toUpperCase()} AL DOCENTE?`,
                html: `<div style="text-align:left; margin:15px 0;">
                        <p><strong>Docente:</strong> ${professorName}</p>
                        <p><strong>Estatus actual:</strong> <span class="professor-status status-${currentStatus === 'Activo' ? 'active' : 'inactive'}">${currentStatus}</span></p>
                        <p><strong>Nuevo estatus:</strong> <span class="professor-status status-${willBeActive ? 'active' : 'inactive'}">${statusText}</span></p>
                      </div>`,
                icon: icon,
                showCancelButton: true,
                confirmButtonText: `Sí, ${action}`,
                cancelButtonText: 'Cancelar',
                confirmButtonColor: willBeActive ? '#28a745' : '#dc3545',
                customClass: {
                    popup: 'custom-swal-popup',
                    confirmButton: 'custom-swal-confirm-btn',
                    cancelButton: 'custom-swal-cancel-btn'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Envía el formulario si se confirma
                    form.submit();
                }
            });
        });
    });
});
</script>