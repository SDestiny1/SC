@extends('layouts.app')

@section('title', 'Gestión de Alumnos')

@section('content')
    <!-- Contenido principal -->
    <main class="main-content">
        <header class="header">
            <h1>Gestión de Alumnos</h1>
            <div class="user-info">
                <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Usuario">
                <span>María González</span>
            </div>
        </header>

        <!-- Barra de herramientas -->
        <div class="toolbar">
            <form action="{{ route('students.index') }}" method="GET" class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Buscar alumnos por nombre, matrícula o carrera..." 
                       value="{{ request('search') }}">
            </form>
            <div class="action-buttons">
                <button class="btn btn-secondary">
                    <i class="fas fa-file-export"></i>
                    <span>Exportar</span>
                </button>
                <button class="btn btn-outline" id="toggleFilters">
                    <i class="fas fa-filter"></i>
                    <span>Filtros</span>
                </button>
            </div>
        </div>

      <!-- Filtros -->
<div class="filters" id="filtersSection" style="display: none;">
    <form action="{{ route('students.index') }}" method="GET">
        <div class="filter-group">
            <label for="carreraID">Carrera</label>
            <select id="carreraID" name="carreraID" class="form-control">
                <option value="">Todas las carreras</option>
                @foreach($carreras as $id => $nombre)
                    <option value="{{ $id }}" {{ request('carreraID') == $id ? 'selected' : '' }}>
                        {{ $nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="filter-group">
            <label for="semestre">Semestre</label>
            <select id="semestre" name="semestre" class="form-control">
                <option value="">Todos los semestres</option>
                @foreach($semestres as $semestre)
                    <option value="{{ $semestre }}" {{ request('semestre') == $semestre ? 'selected' : '' }}>
                        {{ $semestre }}° Semestre
                    </option>
                @endforeach
            </select>
        </div>
        
        <div class="filter-group">
            <label for="estatus">Estatus</label>
            <select id="estatus" name="estatus" class="form-control">
                <option value="">Todos los estatus</option>
                <option value="active" {{ request('estatus') == 'active' ? 'selected' : '' }}>Activo</option>
                <option value="inactive" {{ request('estatus') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>
        
        <div class="filter-group">
            <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">Limpiar Filtros</a>
        </div>
    </form>
</div>

        <!-- Tabla de alumnos -->
        <div class="students-table-container">
            <table class="students-table">
                <thead>
                    <tr>
                        <th>Alumno</th>
                        <th>Matrícula</th>
                        <th>Carrera</th>
                        <th>Semestre</th>
                        <th>Correo</th>
                        <th>Estatus</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $student)
                    <tr>
                        <td>
                            <div class="student-name">
                                <img src="{{ $student->fotoPerfil ?? 'https://randomuser.me/api/portraits/men/32.jpg' }}" 
                                     alt="Alumno" class="student-avatar">
                                <span>{{ $student->nombre }} {{ $student->apellidoPaterno }} {{ $student->apellidoMaterno }}</span>
                            </div>
                        </td>
                        <td>{{ $student->matricula ?? substr($student->_id, 0, 8) }}</td>
                        <td>{{ $student->grupo->carrera->nombreCarrera ?? 'Sin carrera' }}</td>                       
                        <td>{{ $student->grupo->semestre ?? 'Sin semestre' }}°</td>
                        <td>{{ $student->_id }}</td>
                        <td>
                            <span class="status-badge status-{{ $student->activo ? 'active' : 'inactive' }}">
                                {{ $student->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
<td>
    <div class="action-icons">
        <a href="{{ route('students.show', $student->_id) }}" class="action-icon" title="Ver perfil">
            <i class="fas fa-eye"></i>
        </a>
        <a href="mailto:{{ $student->_id }}" class="action-icon" title="Enviar mensaje">
            <i class="fas fa-envelope"></i>
        </a>
        <form action="{{ route('students.destroy', $student->_id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="action-icon" title="{{ $student->activo ? 'Desactivar' : 'Activar' }}">
                <i class="fas {{ $student->activo ? 'fa-user-slash' : 'fa-user-check' }}"></i>
            </button>
        </form>
    </div>
</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center">No se encontraron alumnos</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Paginación -->
            <div class="pagination">
                <div class="pagination-info">
                    Mostrando {{ $students->firstItem() }} - {{ $students->lastItem() }} de {{ $students->total() }} alumnos
                </div>
                <div class="pagination-controls">
                    {{ $students->appends(request()->query())->links() }}
                </div>
            </div>
        </div>
    </main>
    


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Mostrar/ocultar filtros (mantenemos esta funcionalidad)
    const toggleFilters = document.getElementById('toggleFilters');
    const filtersSection = document.getElementById('filtersSection');
    
    toggleFilters.addEventListener('click', function() {
        filtersSection.style.display = filtersSection.style.display === 'none' ? 'block' : 'none';
    });
    
    // Configuración de SweetAlert para cambiar estatus
    const statusForms = document.querySelectorAll('form[method="POST"]');
    statusForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const studentRow = form.closest('tr');
            const studentName = studentRow.querySelector('.student-name span').textContent;
            const currentStatus = studentRow.querySelector('.status-badge').textContent.trim();
            const willBeActive = form.querySelector('i').classList.contains('fa-user-check');
            const action = willBeActive ? 'reactivar' : 'desactivar';
            const statusText = willBeActive ? 'Activo' : 'Inactivo';
            const icon = willBeActive ? 'success' : 'warning';
            
            Swal.fire({
                title: `¿${action.toUpperCase()} AL ALUMNO?`,
                html: `<div style="text-align:left; margin:15px 0;">
                        <p><strong>Alumno:</strong> ${studentName}</p>
                        <p><strong>Estatus actual:</strong> <span class="status-badge status-${currentStatus === 'Activo' ? 'active' : 'inactive'}">${currentStatus}</span></p>
                        <p><strong>Nuevo estatus:</strong> <span class="status-badge status-${willBeActive ? 'active' : 'inactive'}">${statusText}</span></p>
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