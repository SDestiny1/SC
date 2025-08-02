@extends('layouts.app')

@section('title', 'Noticias')

@section('content')
<main class="main-content">
            <header class="header">
            <h1><i class="fas fa-calendar-alt"></i> Noticias</h1>
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
                <input type="text" id="search-input" placeholder="Buscar publicaciones por título, autor o contenido...">
            </div>
            <div class="action-buttons">
                <a href="{{ route('posts.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    <span>Crear Noticia</span>
                </a>
                <button class="btn btn-secondary" id="toggle-filters">
                    <i class="fas fa-filter"></i>
                    <span>Filtros</span>
                </button>
            </div>
        </div>

      <!-- Filtros simplificados -->
    <div class="filters" id="filters-section" style="display: none;">
        <div class="filter-group">
            <label for="filter-tipo">Tipo</label>
            <select id="filter-tipo" class="filter-select">
                <option value="">Todos</option>
                <option value="pregunta">Pregunta</option>
                <option value="aviso">Aviso</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="filter-estado">Estado</label>
            <select id="filter-estado" class="filter-select">
                <option value="">Todos</option>
                <option value="true">Activo</option>
                <option value="false">Inactivo</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="filter-visibilidad">Visibilidad</label>
            <select id="filter-visibilidad" class="filter-select">
                <option value="">Todas</option>
                <option value="todos">Todos</option>
                <option value="grupo">Solo grupo</option>
            </select>
        </div>
        <div class="filter-group">
            <label for="filter-grupo">Grupo</label>
            <select id="filter-grupo" class="filter-select">
                <option value="">Todos</option>
                <option value="dsm_5a_1">DSM 5A 1</option>
                <option value="dsm_5d_1">DSM 5D 1</option>
            </select>
        </div>
        <div class="filter-actions">
            <button id="apply-filters" class="btn btn-primary">Aplicar</button>
            <button id="reset-filters" class="btn btn-secondary">Limpiar</button>
        </div>
    </div>

    <div class="noticias-container">
        <h1 class="page-title">Últimas Noticias</h1>

        @forelse ($noticias as $noticia)
            <div class="noticia-card">
                <h2>{{ $noticia['titulo'] }}</h2>
                <p class="fecha">
                    Publicada el {{ \Carbon\Carbon::createFromTimestampMs($noticia['fechaPublicacion'])->format('d/m/Y H:i') }}

                </p>
                @if (!empty($noticia['imagenURL']))
                    <img src="{{ asset($noticia['imagenURL']) }}" alt="Imagen de la noticia" class="noticia-img">
                @endif
                <p>{{ $noticia['contenido'] }}</p>
            </div>
        @empty
            <p>No hay noticias disponibles.</p>
        @endforelse
    </div>
</main>

<style>
.noticias-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 2rem;
}
.noticia-card {
    border: 1px solid #ddd;
    padding: 1rem;
    margin-bottom: 1.5rem;
    border-radius: 8px;
    background-color: #fdfdfd;
}
.noticia-card h2 {
    margin-bottom: 0.5rem;
}
.noticia-card .fecha {
    font-size: 0.9rem;
    color: #666;
}
.noticia-img {
    max-width: 100%;
    margin: 1rem 0;
    border-radius: 6px;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos del DOM
    const toggleFiltersBtn = document.getElementById('toggle-filters');
    const filtersSection = document.getElementById('filters-section');
    const applyFiltersBtn = document.getElementById('apply-filters');
    const resetFiltersBtn = document.getElementById('reset-filters');
    const searchInput = document.getElementById('search-input');
    const publicationContainer = document.querySelector('.publications-container');
    
    // Mostrar/Ocultar filtros
    toggleFiltersBtn.addEventListener('click', function() {
        filtersSection.style.display = filtersSection.style.display === 'none' ? 'grid' : 'none';
    });
    
    // Función principal de filtrado
    function applyFilters() {
        const filters = {
            tipo: document.getElementById('filter-tipo').value,
            estado: document.getElementById('filter-estado').value,
            visibilidad: document.getElementById('filter-visibilidad').value,
            grupo: document.getElementById('filter-grupo').value,
            busqueda: searchInput.value.toLowerCase()
        };
        
        let hasVisiblePublications = false;
        
        document.querySelectorAll('.publication').forEach(pub => {
            const matchesTipo = !filters.tipo || pub.dataset.tipo === filters.tipo;
            const matchesEstado = !filters.estado || pub.dataset.activo === filters.estado;
            const matchesVisibilidad = !filters.visibilidad || pub.dataset.visibilidad === filters.visibilidad;
            const matchesGrupo = !filters.grupo || pub.dataset.grupo === filters.grupo;
            const matchesBusqueda = !filters.busqueda || 
                                  pub.dataset.contenido.includes(filters.busqueda) || 
                                  pub.dataset.autor.includes(filters.busqueda);
            
            const shouldShow = matchesTipo && matchesEstado && matchesVisibilidad && 
                             matchesGrupo && matchesBusqueda;
            
            pub.style.display = shouldShow ? 'block' : 'none';
            if (shouldShow) hasVisiblePublications = true;
        });
        
        // Mostrar mensaje si no hay resultados
        const noResults = document.querySelector('.no-results-message');
        if (!hasVisiblePublications) {
            if (!noResults) {
                const message = document.createElement('p');
                message.className = 'no-results-message';
                message.textContent = 'No se encontraron publicaciones con los filtros aplicados.';
                publicationContainer.appendChild(message);
            }
        } else if (noResults) {
            noResults.remove();
        }
    }
    
    // Event listeners
    applyFiltersBtn.addEventListener('click', applyFilters);
    resetFiltersBtn.addEventListener('click', function() {
        document.querySelectorAll('.filter-select').forEach(select => {
            select.value = '';
        });
        searchInput.value = '';
        applyFilters();
    });
    searchInput.addEventListener('input', applyFilters);
    
    // Aplicar filtros al cargar
    applyFilters();
});
</script>
