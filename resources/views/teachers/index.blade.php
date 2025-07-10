@extends('layouts.app')

@section('title', 'Gestión de Docentes')

@section('content')
    <!-- Contenido principal -->
    <main class="main-content">
        <header class="header">
            <h1><i class="fas fa-chalkboard-teacher"></i> Gestión de Personal Académico</h1>
            <div class="user-info">
                <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Usuario">
                <span>María González</span>
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
            
            <div class="stat-card">
                <div class="stat-label"><i class="fas fa-clock"></i> Docentes Activos</div>
                <div class="stat-value">{{ $activeTeachers }}</div>
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
                    <a href="{{ route('teachers.edit', $teacher->_id) }}" class="action-btn secondary">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="mailto:{{ $teacher->_id }}" class="action-btn">
                        <i class="fas fa-envelope"></i> Contactar
                    </a>
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
        });
    </script>