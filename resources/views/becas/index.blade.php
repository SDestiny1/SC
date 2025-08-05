@extends('layouts.app')

@section('title', 'Becas')

@section('content')
<main class="main-content">
    <header class="header">
        <h1><i class="fas fa-award"></i> Becas Disponibles</h1>
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
                <input type="text" name="search" placeholder="Buscar becas por título, institución o descripción..." 
                       value="{{ request('search') }}">
                <button type="submit" class="search-button">Buscar</button>
            </div>
            
            <!-- Filtros organizados horizontalmente -->
            <div class="filters-row">
                <div class="filter-item">
                    <label>Tipo de beca:</label>
                    <select name="tipo_beca">
                        <option value="">Todos</option>
                        <option value="con_beneficio" {{ request('tipo_beca') == 'con_beneficio' ? 'selected' : '' }}>Con beneficio económico</option>
                        <option value="sin_beneficio" {{ request('tipo_beca') == 'sin_beneficio' ? 'selected' : '' }}>Sin beneficio económico</option>
                    </select>
                </div>
                
                <div class="filter-item">
                    <label>Promedio:</label>
                    <select name="promedio">
                        <option value="">Todos</option>
                        <option value="con_promedio" {{ request('promedio') == 'con_promedio' ? 'selected' : '' }}>Requiere promedio</option>
                        <option value="sin_promedio" {{ request('promedio') == 'sin_promedio' ? 'selected' : '' }}>No requiere</option>
                    </select>
                </div>
                
                <div class="filter-item">
                    <label>Estado:</label>
                    <select name="estado">
                        <option value="">Todos</option>
                        <option value="activas" {{ request('estado') == 'activas' ? 'selected' : '' }}>Activas</option>
                        <option value="proximas" {{ request('estado') == 'proximas' ? 'selected' : '' }}>Próximas</option>
                    </select>
                </div>
                
                <div class="filter-actions">
                    <button type="submit" class="filter-button">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                    <a href="{{ route('becas.index') }}" class="clear-button">
                        <i class="fas fa-times"></i> Limpiar
                    </a>
                </div>
            </div>
        </form>
    </div>

    <div class="action-section">
        <a href="{{ route('becas.create') }}" class="btn btn-create">
            <i class="fas fa-plus"></i> Crear nueva beca
        </a>
    </div>

    <div class="becas-container">
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
                        <i class="fas fa-money-bill-wave"></i>
                        <span>{{ isset($beca->monto) ? '$'.number_format($beca->monto, 2).' MXN' : 'Monto no especificado' }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-calendar-day"></i>
                        <span>Del {{ \Carbon\Carbon::parse($beca->fechaInicio)->format('d/m/Y') }} al {{ \Carbon\Carbon::parse($beca->fechaFin)->format('d/m/Y') }}</span>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-star"></i>
                        <span>Promedio mínimo: {{ $beca->promedioMinimo ?? 'N/A' }}</span>
                    </div>
                </div>
                
                <div class="beca-actions">
                    <a href="{{ route('becas.show', $beca->_id) }}" class="btn btn-primary">
                        Ver detalles
                    </a>
                    @if($beca->url)
                    <a href="{{ $beca->url }}" target="_blank" class="btn btn-secondary">
                        Sitio oficial
                    </a>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Aplicar filtros seleccionados si existen en la URL
        const urlParams = new URLSearchParams(window.location.search);
        
        if(urlParams.has('tipo_beca')) {
            document.getElementById('tipo_beca').value = urlParams.get('tipo_beca');
        }
        
        if(urlParams.has('promedio')) {
            document.getElementById('promedio').value = urlParams.get('promedio');
        }
        
        if(urlParams.has('nombre')) {
            document.getElementById('nombre').value = urlParams.get('nombre');
        }
        
        // Limpiar filtros
        document.querySelector('.btn-reset').addEventListener('click', function() {
            window.location.href = "{{ route('becas.index') }}";
        });
    });
</script>