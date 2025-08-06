@extends('layouts.app')

@section('title', 'Becas')

@section('content')
<main class="main-content">
    <header class="header">
        <h1><i class="fas fa-award"></i> Becas y Programas Disponibles</h1>
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

    <!-- Sección de búsqueda y filtros mejorada -->
    <div class="search-filters-section">
        <form id="filters-form" method="GET" action="{{ route('becas.index') }}">
            <!-- Campo de búsqueda principal -->
            <div class="search-box">
                <i class="fas fa-search"></i>
                <input type="text" name="search" id="search-input" placeholder="Buscar becas y programas por título, institución o descripción..." 
                       value="{{ request('search') }}">
                <div class="search-loading" id="search-loading" style="display: none;">
                    <i class="fas fa-spinner fa-spin"></i>
                </div>
            </div>
            
            <!-- Filtros organizados horizontalmente -->
            <div class="filters-row">
                <div class="filter-item">
                    <label>Tipo:</label>
                    <select name="tipo" id="tipo-filter">
                        <option value="">Todos</option>
                        <option value="beca" {{ request('tipo') == 'beca' ? 'selected' : '' }}>Beca</option>
                        <option value="programa" {{ request('tipo') == 'programa' ? 'selected' : '' }}>Programa</option>
                    </select>
                </div>
                
                <div class="filter-item">
                    <label>Estado:</label>
                    <select name="estado" id="estado-filter">
                        <option value="">Todos</option>
                        <option value="activas" {{ request('estado') == 'activas' ? 'selected' : '' }}>Activas</option>
                        <option value="proximas" {{ request('estado') == 'proximas' ? 'selected' : '' }}>Próximas</option>
                    </select>
                </div>
                
                <div class="filter-actions">
                    <button type="button" class="clear-button" id="clear-filters">
                        <i class="fas fa-times"></i> Limpiar
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="action-section">
        <a href="{{ route('becas.create') }}" class="btn btn-create">
            <i class="fas fa-plus"></i> Crear nueva beca/programa
        </a>
    </div>

    <div class="becas-container" id="becas-container">
        @foreach($becas as $beca)
        <div class="beca-card">
            <div class="beca-header">
                <h2>{{ $beca->titulo }}</h2>
                <span class="institucion">{{ $beca->institucion }}</span>
            </div>
            
            <div class="beca-body">
                <p class="descripcion">{{ Str::limit($beca->descripcion, 200) }}</p>
                
                <div class="beca-details">
                    <div class="detail-item">
                        <i class="fas fa-tag"></i>
                        <span>Tipo: {{ ucfirst($beca->tipo) }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-calendar-day"></i>
                        <span>Del {{ \Carbon\Carbon::parse($beca->fechaInicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($beca->fechaFin)->format('d/m/Y') }}</span>
                    </div>
                    @if($beca->tipo === 'beca' && isset($beca->monto))
                    <div class="detail-item">
                        <i class="fas fa-money-bill-wave"></i>
                        <span>${{ number_format($beca->monto, 2) }} MXN</span>
                    </div>
                    @endif
                    <div class="detail-item">
                        <i class="fas fa-user"></i>
                        <span>Autor: {{ $beca->autorID }}</span>
                    </div>
                </div>
                
                <div class="beca-actions">
                    <a href="{{ route('becas.show', $beca->_id) }}" class="btn btn-primary">
                        Ver detalles
                    </a>
                    <a href="{{ $beca->url }}" target="_blank" class="btn btn-secondary">
                        <i class="fas fa-external-link-alt"></i> Sitio oficial
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Indicador de carga -->
    <div id="loading-indicator" class="loading-indicator" style="display: none;">
        <div class="loading-spinner">
            <i class="fas fa-spinner fa-spin"></i>
            <p>Cargando resultados...</p>
        </div>
    </div>
    
    <!-- Mensaje de no resultados -->
    <div id="no-results" class="no-results" style="display: none;">
        <div class="no-results-content">
            <i class="fas fa-search"></i>
            <h3>No se encontraron resultados</h3>
            <p>Intenta ajustar los filtros o términos de búsqueda</p>
        </div>
    </div>
</main>

@section('styles')
<style>
    /* Estilos del header */
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #eaeaea;
    }
    
    /* Nueva sección de búsqueda y filtros */
    .search-filters-section {
        padding: 15px 20px;
        background: #fff;
        border-bottom: 1px solid #eaeaea;
    }
    
    .search-box {
        position: relative;
        margin-bottom: 15px;
        display: flex;
    }
    
    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6c757d;
    }
    
    .search-box input {
        width: 100%;
        padding: 10px 15px 10px 40px;
        border: 1px solid #ddd;
        border-radius: 4px 0 0 4px;
        font-size: 0.95rem;
    }
    
    .search-button {
        padding: 0 20px;
        background: #1e5799;
        color: white;
        border: none;
        border-radius: 0 4px 4px 0;
        cursor: pointer;
        transition: background 0.3s;
    }
    
    .search-button:hover {
        background: #154273;
    }
    
    /* Fila de filtros */
    .filters-row {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        align-items: center;
    }
    
    .filter-item {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .filter-item label {
        font-size: 0.9rem;
        color: #495057;
        white-space: nowrap;
    }
    
    .filter-item select {
        padding: 8px 12px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        background-color: #fff;
        min-width: 180px;
    }
    
    .filter-actions {
        display: flex;
        gap: 10px;
        margin-left: auto;
    }
    
    .filter-button, .clear-button {
        padding: 8px 15px;
        border-radius: 4px;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 5px;
        cursor: pointer;
        transition: all 0.3s;
    }
    
    .filter-button {
        background: #28a745;
        color: white;
        border: none;
    }
    
    .filter-button:hover {
        background: #218838;
    }
    
    .clear-button {
        background: #f8f9fa;
        color: #6c757d;
        border: 1px solid #ddd;
        text-decoration: none;
    }
    
    .clear-button:hover {
        background: #e2e6ea;
    }
    
    /* Sección de acciones */
    .action-section {
        padding: 10px 20px;
        background: #fff;
        border-bottom: 1px solid #eaeaea;
    }
    
    .btn-create {
        padding: 8px 15px;
        background: #1e5799;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.9rem;
        transition: background 0.3s;
    }
    
    .btn-create:hover {
        background: #154273;
    }
    
    .header h1 {
        margin: 0;
        color: #1e5799;
        font-size: 1.8rem;
    }
    
    /* Información de usuario */
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
        font-weight: 600;
        color: #555;
    }
    
    /* Contenedor del botón de creación */
    .create-button-container {
        padding: 15px 20px;
        text-align: right;
        background: #fff;
        border-bottom: 1px solid #eaeaea;
    }
    
    /* Estilos del botón */
    .btn {
        padding: 10px 20px;
        border-radius: 4px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-primary {
        background-color: #1e5799;
        color: white;
        border: none;
    }
    
    .btn-primary:hover {
        background-color: #154273;
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    
    .btn i {
        font-size: 1rem;
    }
    
    /* Contenedor de becas (manteniendo tus estilos existentes) */
    .becas-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
        gap: 20px;
        padding: 20px;
    }
    
    .beca-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.3s ease;
    }
    
    .beca-card:hover {
        transform: translateY(-5px);
    }
    
    .beca-header {
        background: linear-gradient(135deg, #1e5799 0%,#207cca 100%);
        color: white;
        padding: 15px;
    }
    
    .beca-header h2 {
        margin: 0;
        font-size: 1.2rem;
    }
    
    .institucion {
        font-size: 0.9rem;
        opacity: 0.9;
    }
    
    .beca-body {
        padding: 15px;
    }
    
    .descripcion {
        color: #555;
        margin-bottom: 15px;
    }
    
    .beca-details {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        margin-bottom: 15px;
    }
    
    .detail-item {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.9rem;
    }
    
    .beca-actions {
        display: flex;
        gap: 10px;
        margin-top: 15px;
    }
    
    .btn-secondary {
        background: #6c757d;
        color: white;
    }
    
    .btn-secondary:hover {
        background: #5a6268;
    }
        /* Nueva sección de acciones */
    .action-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 20px;
        background: #fff;
        border-bottom: 1px solid #eaeaea;
        flex-wrap: wrap;
        gap: 15px;
    }
    
    .filters-container {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
        flex-grow: 1;
    }
    
    .filter-group {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    
    label {
        font-size: 0.85rem;
        color: #555;
        white-space: nowrap;
    }
    
         .form-control-sm {
         padding: 5px 10px;
         border-radius: 4px;
         border: 1px solid #ddd;
         font-size: 0.85rem;
         height: 32px;
     }
     
     /* Estilos para filtros dinámicos */
     .search-loading {
         position: absolute;
         right: 15px;
         top: 50%;
         transform: translateY(-50%);
         color: #1e5799;
     }
     
     .loading-indicator {
         display: flex;
         justify-content: center;
         align-items: center;
         padding: 40px 20px;
         background: #fff;
     }
     
     .loading-spinner {
         text-align: center;
         color: #1e5799;
     }
     
     .loading-spinner i {
         font-size: 2rem;
         margin-bottom: 10px;
     }
     
     .loading-spinner p {
         margin: 0;
         font-size: 1rem;
         color: #666;
     }
     
     .no-results {
         display: flex;
         justify-content: center;
         align-items: center;
         padding: 60px 20px;
         background: #fff;
     }
     
     .no-results-content {
         text-align: center;
         color: #666;
     }
     
     .no-results-content i {
         font-size: 3rem;
         color: #ddd;
         margin-bottom: 15px;
     }
     
     .no-results-content h3 {
         margin: 0 0 10px 0;
         color: #333;
     }
     
     .no-results-content p {
         margin: 0;
         font-size: 1rem;
     }
     
     /* Animación de fade para las tarjetas */
     .beca-card {
         animation: fadeIn 0.3s ease-in;
     }
     
     @keyframes fadeIn {
         from {
             opacity: 0;
             transform: translateY(10px);
         }
         to {
             opacity: 1;
             transform: translateY(0);
         }
     }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Variables globales
        let searchTimeout;
        const searchInput = document.getElementById('search-input');
        const tipoFilter = document.getElementById('tipo-filter');
        const estadoFilter = document.getElementById('estado-filter');
        const clearFiltersBtn = document.getElementById('clear-filters');
        const becasContainer = document.getElementById('becas-container');
        const loadingIndicator = document.getElementById('loading-indicator');
        const noResults = document.getElementById('no-results');
        const searchLoading = document.getElementById('search-loading');
        
        // Aplicar filtros seleccionados si existen en la URL
        const urlParams = new URLSearchParams(window.location.search);
        
        if(urlParams.has('tipo')) {
            tipoFilter.value = urlParams.get('tipo');
        }
        
        if(urlParams.has('estado')) {
            estadoFilter.value = urlParams.get('estado');
        }
        
        if(urlParams.has('search')) {
            searchInput.value = urlParams.get('search');
        }
        
        // Función para actualizar la URL sin recargar la página
        function updateURL(params) {
            const url = new URL(window.location);
            Object.keys(params).forEach(key => {
                if (params[key] === '' || params[key] === null) {
                    url.searchParams.delete(key);
                } else {
                    url.searchParams.set(key, params[key]);
                }
            });
            window.history.pushState({}, '', url);
        }
        
        // Función para mostrar/ocultar indicadores
        function showLoading() {
            loadingIndicator.style.display = 'flex';
            becasContainer.style.display = 'none';
            noResults.style.display = 'none';
        }
        
        function hideLoading() {
            loadingIndicator.style.display = 'none';
            becasContainer.style.display = 'grid';
        }
        
        function showNoResults() {
            loadingIndicator.style.display = 'none';
            becasContainer.style.display = 'none';
            noResults.style.display = 'flex';
        }
        
                 // Función para realizar la búsqueda AJAX
         function performSearch() {
             const searchTerm = searchInput.value.trim();
             const tipo = tipoFilter.value;
             const estado = estadoFilter.value;
             
             // Actualizar URL
             updateURL({
                 search: searchTerm,
                 tipo: tipo,
                 estado: estado
             });
             
             // Mostrar indicador de carga
             showLoading();
             
             // Construir URL con parámetros
             const params = new URLSearchParams();
             if (searchTerm) params.append('search', searchTerm);
             if (tipo) params.append('tipo', tipo);
             if (estado) params.append('estado', estado);
             
             const url = `{{ route('becas.index') }}?${params.toString()}`;
             
             // Realizar petición AJAX
             fetch(url, {
                 headers: {
                     'X-Requested-With': 'XMLHttpRequest',
                     'X-CSRF-TOKEN': '{{ csrf_token() }}'
                 }
             })
            .then(response => response.text())
                         .then(html => {
                 // La respuesta AJAX ya contiene solo las tarjetas
                 if (html.trim() !== '') {
                     becasContainer.innerHTML = html;
                     hideLoading();
                 } else {
                     showNoResults();
                 }
             })
            .catch(error => {
                console.error('Error en la búsqueda:', error);
                hideLoading();
                showNoResults();
            });
        }
        
        // Event listeners para filtros dinámicos
        
        // Búsqueda con debounce (esperar 500ms después de que el usuario deje de escribir)
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchLoading.style.display = 'block';
            
            searchTimeout = setTimeout(() => {
                performSearch();
                searchLoading.style.display = 'none';
            }, 500);
        });
        
        // Filtros de tipo y estado (cambio inmediato)
        tipoFilter.addEventListener('change', performSearch);
        estadoFilter.addEventListener('change', performSearch);
        
        // Limpiar filtros
        clearFiltersBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Limpiar campos
            searchInput.value = '';
            tipoFilter.value = '';
            estadoFilter.value = '';
            
            // Actualizar URL
            updateURL({});
            
            // Realizar búsqueda
            performSearch();
        });
        
        // Prevenir envío del formulario
        document.getElementById('filters-form').addEventListener('submit', function(e) {
            e.preventDefault();
            performSearch();
        });
    });
</script>