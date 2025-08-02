@extends('layouts.app')

@section('title', 'Gestión de Alumnos')

@section('content')
    <!-- Contenido principal -->
    <main class="main-content">
        <header class="header">
            <h1>Gestión de Alumnos</h1>
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

        <!-- Barra de herramientas -->
        <div class="toolbar">
            <form action="{{ route('students.index') }}" method="GET" class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" name="search" placeholder="Buscar alumnos por nombre, matrícula o carrera..." 
                       value="{{ request('search') }}">
            </form>
            <div class="action-buttons">
            <!-- Reemplazar el botón actual con este: -->
            <button class="btn btn-secondary" id="importButton">
                <i class="fas fa-file-import"></i>
                <span>Importar</span>
            </button>
            <input type="file" id="fileInput" accept=".xlsx, .xls, .csv" style="display: none;">
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
                    <button type="button" class="action-icon view-student-btn" data-id="{{ $student->_id }}" title="Ver perfil">
                        <i class="fas fa-eye"></i>
                    </button>
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
        
<!-- Modal de información del alumno - Versión corregida -->
<div id="studentModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <h3>Perfil del Alumno</h3>
            <button class="close-modal">&times;</button>
        </div>
        <div id="studentModalBody" class="modal-content">
            <!-- El contenido se cargará aquí dinámicamente -->
        </div>
    </div>
</div>

</main>
    
<style>
    .action-icon i {
    color: white;
}

    </style>

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
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('studentModal');
    const modalBody = document.getElementById('studentModalBody');
    
    // Función para abrir el modal
    function openModal() {
        modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }
    
    // Función para cerrar el modal
    function closeModal() {
        modal.style.display = 'none';
        document.body.style.overflow = 'auto';
    }
    
    // Eventos para abrir el modal
    document.querySelectorAll('.view-student-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const studentId = this.getAttribute('data-id');
            
            // Mostrar loader
            modalBody.innerHTML = `
                <div class="loading-spinner">
                    <i class="fas fa-spinner fa-spin"></i>
                    Cargando información del alumno...
                </div>
            `;
            
            openModal();
            
            // Cargar datos del alumno
            fetch(`/students/${studentId}`)
                .then(response => response.text())
                .then(html => {
                    modalBody.innerHTML = html;
                })
                .catch(error => {
                    modalBody.innerHTML = `
                        <div class="error-message">
                            <i class="fas fa-exclamation-triangle"></i>
                            <p>Error al cargar el perfil</p>
                            <button onclick="closeModal()">Cerrar</button>
                        </div>
                    `;
                });
        });
    });
    
    // Eventos para cerrar el modal
    document.querySelector('.close-modal').addEventListener('click', closeModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeModal();
    });
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.style.display === 'flex') closeModal();
    });
    
    // Hacer la función accesible globalmente
    window.closeModal = closeModal;
});
</script>

<script>
    // Agregar este script al final de tu sección de scripts
document.getElementById('importButton').addEventListener('click', function() {
    document.getElementById('fileInput').click();
});

document.getElementById('fileInput').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (!file) return;
    
    // Validar extensión del archivo
    const validExtensions = ['xlsx', 'xls', 'csv'];
    const extension = file.name.split('.').pop().toLowerCase();
    
    if (!validExtensions.includes(extension)) {
        Swal.fire({
            title: 'Error',
            text: 'Formato de archivo no válido. Por favor, suba un archivo Excel (.xlsx, .xls) o CSV.',
            icon: 'error'
        });
        return;
    }
    
    // Mostrar confirmación antes de importar
    Swal.fire({
        title: 'Confirmar Importación',
        html: `¿Desea importar el archivo <strong>${file.name}</strong>?<br><br>
              <small>Verifique que el archivo tenga la estructura correcta antes de continuar.</small>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Importar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            importStudents(file);
        } else {
            // Resetear el input de archivo
            document.getElementById('fileInput').value = '';
        }
    });
});

async function importStudents(file) {
    const formData = new FormData();
    formData.append('file', file);
    
    Swal.fire({
        title: 'Procesando archivo',
        html: 'Por favor espere mientras validamos y procesamos los datos...',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    try {
        const response = await fetch('{{ route("students.import") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (response.ok) {
            let html = `Se importaron ${result.imported} estudiantes correctamente.`;
            
            if (result.skipped > 0) {
                html += `<br><br><strong>${result.skipped} registros omitidos:</strong><br>`;
                html += result.errors.join('<br>');
            }
            
            Swal.fire({
                title: result.imported > 0 ? 'Importación exitosa' : 'Problemas en la importación',
                html: html,
                icon: result.imported > 0 ? 'success' : 'warning'
            }).then(() => {
                if (result.imported > 0) {
                    window.location.reload();
                }
            });
        } else {
            Swal.fire({
                title: 'Error en la importación',
                html: result.message || 'Ocurrió un error al procesar el archivo.',
                icon: 'error'
            });
        }
    } catch (error) {
        Swal.fire({
            title: 'Error',
            text: 'Ocurrió un error al comunicarse con el servidor.',
            icon: 'error'
        });
    }
}
</script>

<style>
/* Estilos base del modal */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
}

.modal-container {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow: auto;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
}

.modal-header {
    padding: 16px 24px;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: linear-gradient(to right, #3b82f6, #6366f1);
    color: white;
    position: sticky;
    top: 0;
    z-index: 10;
}

.modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.close-modal {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: white;
    line-height: 1;
}

.modal-content {
    padding: 24px;
}

/* Estilos específicos para el contenido del perfil */
.student-profile {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.student-header {
    text-align: center;
    margin-bottom: 8px;
}

.student-name1 {
    font-size: 1.5rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 4px;
}

.student-info1 {
    display: grid;
    grid-template-columns: max-content 1fr;
    gap: 12px 16px;
    align-items: center;
}

.student-info-label {
    font-weight: 600;
    color: #4b5563;
}

.student-info-value {
    color: #1f2937;
}

.student-divider {
    border: none;
    height: 1px;
    background-color: #e5e7eb;
    margin: 16px 0;
}
</style>