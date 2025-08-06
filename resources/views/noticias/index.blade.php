@extends('layouts.app')

@section('title', 'Noticias')

@section('content')
<main class="main-content">
    <header class="header">
        <h1><i class="fas fa-newspaper"></i> Noticias</h1>
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
        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" id="search-input" placeholder="Buscar noticias por título o contenido...">
        </div>
        <div class="action-buttons">
            <a href="{{ route('noticias.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                <span>Crear Noticia</span>
            </a>
            <button class="btn btn-secondary" id="toggle-filters">
                <i class="fas fa-filter"></i>
                <span>Filtros</span>
            </button>
        </div>
    </div>

    <!-- Filtros -->
    <div class="filters" id="filters-section" style="display: none;">
        <div class="filter-group">
            <label for="filter-estado">Estado</label>
            <select id="filter-estado" class="filter-select">
                <option value="">Todos</option>
                <option value="true">Activo</option>
                <option value="false">Inactivo</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="filter-fecha">Fecha</label>
            <select id="filter-fecha" class="filter-select">
                <option value="">Todas</option>
                <option value="hoy">Hoy</option>
                <option value="semana">Esta semana</option>
                <option value="mes">Este mes</option>
            </select>
        </div>
        <div class="filter-actions">
            <button id="apply-filters" class="btn btn-primary">Aplicar</button>
            <button id="reset-filters" class="btn btn-secondary">Limpiar</button>
        </div>
    </div>

    <div class="noticias-container">
        @forelse ($noticias as $noticia)
            <div class="noticia-card @if(!$noticia->activo) inactive @endif" 
                 data-titulo="{{ strtolower($noticia->titulo) }}" 
                 data-contenido="{{ strtolower($noticia->contenido) }}"
                 data-activo="{{ $noticia->activo ? 'true' : 'false' }}"
                 data-fecha="{{ $noticia->fechaPublicacion->format('Y-m-d') }}">
                <div class="noticia-header">
                    <h2>{{ $noticia->titulo }}</h2>
                    <div class="noticia-status">
                        <span class="status-badge {{ $noticia->activo ? 'active' : 'inactive' }}">
                            {{ $noticia->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                        <div class="noticia-actions">
                            <a href="{{ route('noticias.edit', $noticia->_id) }}" class="action-btn edit" title="Editar">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('noticias.toggle-status', $noticia->_id) }}" method="POST" class="d-inline toggle-status-form">
    @csrf
    @method('PATCH')
    <button type="submit" class="action-btn status" title="{{ $noticia->activo ? 'Desactivar' : 'Activar' }}">
        <i class="fas {{ $noticia->activo ? 'fa-eye-slash' : 'fa-eye' }}"></i>
    </button>
</form>

                        </div>
                    </div>
                </div>
                
                <p class="fecha">
                    <i class="far fa-calendar-alt"></i>
                    Publicada el {{ $noticia->fechaPublicacion->format('d/m/Y H:i') }}
                </p>
                
                @if($noticia->imagenURL)
                    <img src="{{ $noticia->imagenURL }}" alt="Imagen de noticia" class="noticia-img">
                @endif

                <div class="noticia-content">
                    {{ Str::limit($noticia->contenido, 200) }}
                </div>
            </div>
        @empty
            <div class="no-results">
                <i class="fas fa-newspaper"></i>
                <p>No hay noticias disponibles</p>
            </div>
        @endforelse
    </div>
</main>

<style>
/* Estilos generales */
.main-content {
    padding: 20px;
    max-width: 1400px;
    margin: 0 auto;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.header h1 {
    font-size: 2rem;
    color: #333;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 10px;
}

.user-info img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
}

.user-info span {
    font-weight: 500;
}

/* Barra de herramientas */
.toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    flex-wrap: wrap;
    gap: 15px;
}

.search-bar {
    flex: 1;
    min-width: 300px;
    position: relative;
}

.search-bar i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #666;
}

.search-bar input {
    width: 100%;
    padding: 10px 15px 10px 40px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
}

.action-buttons {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 10px 15px;
    border-radius: 5px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.95rem;
    cursor: pointer;
    border: none;
    transition: all 0.2s;
}

.btn-primary {
    background-color: #4e73df;
    color: white;
}

.btn-primary:hover {
    background-color: #3a5ccc;
}

.btn-secondary {
    background-color: #858796;
    color: white;
}

.btn-secondary:hover {
    background-color: #717384;
}

/* Filtros */
.filters {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 5px;
    margin-bottom: 20px;
    border: 1px solid #e3e6f0;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    margin-bottom: 5px;
    font-weight: 500;
    font-size: 0.9rem;
}

.filter-select {
    padding: 8px 12px;
    border: 1px solid #d1d3e2;
    border-radius: 4px;
    background-color: #fff;
}

.filter-actions {
    display: flex;
    align-items: flex-end;
    gap: 10px;
}

/* Contenedor de noticias */
.noticias-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 20px;
    margin-top: 20px;
}

.noticia-card {
    border: 1px solid #e3e6f0;
    border-radius: 8px;
    padding: 20px;
    background: #fff;
    transition: transform 0.3s, box-shadow 0.3s;
    position: relative;
}

.noticia-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.noticia-card.inactive {
    opacity: 0.8;
    border-left: 4px solid #e74a3b;
}

.noticia-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 10px;
}

.noticia-header h2 {
    margin: 0;
    font-size: 1.3rem;
    color: #4e73df;
    flex: 1;
}

.noticia-status {
    display: flex;
    align-items: center;
    gap: 10px;
}

.status-badge {
    padding: 4px 8px;
    border-radius: 4px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-badge.active {
    background-color: #1cc88a;
    color: white;
}

.status-badge.inactive {
    background-color: #e74a3b;
    color: white;
}

.noticia-actions {
    display: flex;
    gap: 5px;
}

.action-btn {
    background: none;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: background 0.2s;
}

.action-btn.edit {
    color: #36b9cc;
}

.action-btn.edit:hover {
    background: rgba(54, 185, 204, 0.1);
}

.action-btn.status {
    color: #f6c23e;
}

.action-btn.status:hover {
    background: rgba(246, 194, 62, 0.1);
}

.action-btn.delete {
    color: #e74a3b;
}

.action-btn.delete:hover {
    background: rgba(231, 74, 59, 0.1);
}

.fecha {
    color: #858796;
    font-size: 0.85rem;
    margin-bottom: 15px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.noticia-img {
    width: 100%;
    max-height: 200px;
    object-fit: cover;
    border-radius: 5px;
    margin-bottom: 15px;
}

.noticia-content {
    color: #5a5c69;
    line-height: 1.5;
}

.no-results {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px 0;
    color: #858796;
}

.no-results i {
    font-size: 3rem;
    margin-bottom: 15px;
    color: #dddfeb;
}

.no-results p {
    font-size: 1.2rem;
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .noticias-container {
        grid-template-columns: 1fr;
    }
    
    .filters {
        grid-template-columns: 1fr;
    }
    
    .toolbar {
        flex-direction: column;
    }
    
    .search-bar {
        min-width: 100%;
    }
    
    .action-buttons {
        width: 100%;
        justify-content: flex-end;
    }
    /* SweetAlert personalización */
.swal2-popup {
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    border-radius: 8px !important;
}

.swal2-title {
    font-size: 1.3rem !important;
}

.swal2-confirm {
    background-color: #4e73df !important;
    border: none !important;
    padding: 8px 20px !important;
    border-radius: 4px !important;
}

.swal2-cancel {
    background-color: #e74a3b !important;
    border: none !important;
    padding: 8px 20px !important;
    border-radius: 4px !important;
}

.swal2-toast {
    width: 350px !important;
    border-radius: 8px !important;
}
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle filters
    const toggleFiltersBtn = document.getElementById('toggle-filters');
    const filtersSection = document.getElementById('filters-section');
    
    toggleFiltersBtn.addEventListener('click', function() {
        filtersSection.style.display = filtersSection.style.display === 'none' ? 'grid' : 'none';
    });
    
    // Filtrado de noticias
    const searchInput = document.getElementById('search-input');
    const filterEstado = document.getElementById('filter-estado');
    const filterFecha = document.getElementById('filter-fecha');
    const applyFiltersBtn = document.getElementById('apply-filters');
    const resetFiltersBtn = document.getElementById('reset-filters');
    
    function applyFilters() {
        const searchTerm = searchInput.value.toLowerCase();
        const estadoFilter = filterEstado.value;
        const fechaFilter = filterFecha.value;
        
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        const startOfWeek = new Date(today);
        startOfWeek.setDate(today.getDate() - today.getDay());
        
        const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
        
        document.querySelectorAll('.noticia-card').forEach(card => {
            const titulo = card.dataset.titulo;
            const contenido = card.dataset.contenido;
            const activo = card.dataset.activo;
            const fecha = new Date(card.dataset.fecha);
            
            // Aplicar filtros
            const matchesSearch = searchTerm === '' || 
                                titulo.includes(searchTerm) || 
                                contenido.includes(searchTerm);
            
            const matchesEstado = estadoFilter === '' || activo === estadoFilter;
            
            let matchesFecha = true;
            if (fechaFilter === 'hoy') {
                matchesFecha = fecha >= today;
            } else if (fechaFilter === 'semana') {
                matchesFecha = fecha >= startOfWeek;
            } else if (fechaFilter === 'mes') {
                matchesFecha = fecha >= startOfMonth;
            }
            
            if (matchesSearch && matchesEstado && matchesFecha) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    // Event listeners
    searchInput.addEventListener('input', applyFilters);
    filterEstado.addEventListener('change', applyFilters);
    filterFecha.addEventListener('change', applyFilters);
    applyFiltersBtn.addEventListener('click', applyFilters);
    
    resetFiltersBtn.addEventListener('click', function() {
        searchInput.value = '';
        filterEstado.value = '';
        filterFecha.value = '';
        applyFilters();
    });
    
    // Aplicar filtros al cargar
    applyFilters();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuración global de SweetAlert
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    // Confirmación para cambiar estado
    document.querySelectorAll('.toggle-status-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const isActive = form.querySelector('button').title === 'Desactivar';
            const actionText = isActive ? 'desactivar' : 'activar';
            const successText = isActive ? 'desactivada' : 'activada';
            
            Swal.fire({
                title: `¿${isActive ? 'Desactivar' : 'Activar'} noticia?`,
                text: `Estás a punto de ${actionText} esta noticia.`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: `Sí, ${actionText}`,
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar el formulario manualmente
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({
                            _method: 'PATCH'
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Toast.fire({
                                icon: 'success',
                                title: `Noticia ${successText} correctamente`
                            });
                            
                            // Recargar después de 1.5 segundos
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            Toast.fire({
                                icon: 'error',
                                title: 'Error al cambiar el estado'
                            });
                        }
                    })
                    .catch(error => {
                        Toast.fire({
                            icon: 'error',
                            title: 'Error en la solicitud'
                        });
                    });
                }
            });
        });
    });

    // ... (el resto de tu código JavaScript existente)
});
</script>