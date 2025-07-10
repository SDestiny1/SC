@extends('layouts.app') {{-- O el layout que estés usando --}}

@section('title', 'Calendario Escolar')

@section('content')
    <!-- Contenido principal -->
    <main class="main-content">
        <header class="header">
            <h1><i class="fas fa-comment-alt"></i> Gestión de Publicaciones</h1>
            <div class="user-info">
                <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Usuario">
                <span>María González</span>
            </div>
        </header>

        <!-- Barra de herramientas -->
        <div class="toolbar">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar publicaciones por título, autor o contenido...">
            </div>
            <div class="action-buttons">
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    <span>Nueva Publicación</span>
                </button>
                <button class="btn btn-secondary">
                    <i class="fas fa-filter"></i>
                    <span>Filtros</span>
                </button>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filters">
            <div class="filter-group">
                <label for="tipo">Tipo de Publicación</label>
                <select id="tipo">
                    <option value="">Todos los tipos</option>
                    <option value="anuncio">Anuncio Oficial</option>
                    <option value="estudiante">Publicación de Estudiante</option>
                    <option value="profesor">Publicación de Profesor</option>
                    <option value="evento">Evento</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="estado">Estado</label>
                <select id="estado">
                    <option value="">Todos los estados</option>
                    <option value="aprobado">Aprobado</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="rechazado">Rechazado</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="fecha">Fecha de publicación</label>
                <input type="date" id="fecha">
            </div>
            <div class="filter-group">
                <label for="popularidad">Popularidad</label>
                <select id="popularidad">
                    <option value="">Sin filtro</option>
                    <option value="alta">Alta (50+ likes)</option>
                    <option value="media">Media (10-50 likes)</option>
                    <option value="baja">Baja (0-10 likes)</option>
                </select>
            </div>
        </div>

        <!-- Contenedor dinámico de publicaciones -->
        <div class="publications-container">
            @forelse($posts as $post)
                <div class="publication">
                    <div class="publication-header">
                        <div>
                            <h3 class="publication-title">
                                {{ Str::limit($post->contenido, 50) }}
                                <span class="publication-status {{ $post->activo ? 'status-approved' : 'status-rejected' }}">
                                    {{ $post->activo ? 'Aprobado' : 'Inactivo' }}
                                </span>
                            </h3>
                            <div class="publication-meta">
                                <div class="publication-author">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name ?? 'Anonimo') }}" alt="Autor" class="author-avatar">
                                    <span class="author-name">{{ $post->user->name ?? $post->autorID }}</span>
                                </div>
                                <span class="publication-date">
                                    {{ $post->fecha_carbon ? $post->fecha_carbon->format('d/m/Y') : '?' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="publication-content">
                        {{ $post->contenido }}
                    </div>

                    @if($post->imagenURL)
                        <img src="{{ $post->imagenURL }}" alt="Imagen publicación" class="publication-image">
                    @endif

                    <div class="publication-actions">
                        <div class="publication-stats">
                            <div class="stat-item">
                                <i class="fas fa-comment"></i>
                                <span>{{ $post->comentarios_count ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="publication-admin-actions">
                            <form action="{{ route('posts.destroy', $post->_id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta publicación?');">
                                @csrf
                                @method('DELETE')
                                <button class="admin-action-btn btn-reject">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p style="padding: 20px; text-align: center;">No hay publicaciones disponibles.</p>
            @endforelse
        </div>
        
        <!-- Paginación -->
        <div class="pagination">
            <button class="page-btn"><i class="fas fa-chevron-left"></i></button>
            <button class="page-btn active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">3</button>
            <button class="page-btn">4</button>
            <button class="page-btn">5</button>
            <button class="page-btn"><i class="fas fa-chevron-right"></i></button>
        </div>
    </main>
</body>
</html>