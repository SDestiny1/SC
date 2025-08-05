@extends('layouts.app') {{-- O el layout que estés usando --}}

@section('title', 'Calendario Escolar')

@section('content')
    <!-- Contenido principal -->
    <main class="main-content">
        <header class="header">
            <h1><i class="fas fa-comment-alt"></i> Gestión de Publicaciones</h1>
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
                <option value="general">General</option>
                <option value="Ayuda">Ayuda</option>
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

        <!-- Contenedor dinámico de publicaciones -->
        <div class="publications-container">
           @forelse($posts as $post)
    <div class="publication" 
         data-tipo="{{ $post->tipo }}"
         data-activo="{{ $post->activo ? 'true' : 'false' }}"
         data-fecha="{{ $post->fecha ? date('Y-m-d', strtotime($post->fecha)) : '' }}"
         data-visibilidad="{{ $post->visibilidad }}"
         data-grupo="{{ $post->grupoID }}"
         data-contenido="{{ strtolower($post->contenido) }}"
         data-autor="{{ strtolower($post->user->name ?? $post->autorID) }}">
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
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->nombre ?? 'Anonimo') }}" alt="Autor" class="author-avatar">
                                    <span class="author-name">{{ $post->user->nombre ?? $post->autorID }}</span>
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
  <i class="fas fa-thumbs-up"></i>
  <a href="{{ route('posts.show', $post->_id) }}" style="color: inherit; text-decoration: none;">
    {{ $post->likes_count ?? 0 }}
  </a>
</div>
<div class="stat-item comment-btn" data-post-id="{{ $post->_id }}" style="cursor:pointer;">
  <i class="fas fa-comment"></i>
  <span>{{ $post->comentarios_count ?? 0 }}</span>
</div>


                        </div>
                        <div class="publication-admin-actions">
                            <form id="toggle-status-form-{{ $post->_id }}" action="{{ route('posts.toggle-status', $post->_id) }}" method="POST" class="status-form">
                                @csrf
                                @method('PATCH')
                                <button type="button" 
                                        onclick="confirmToggleStatus('{{ $post->_id }}')"
                                        class="action-btn {{ $post->activo ? 'status-inactive' : 'status-active' }}">
                                    <i class="fas {{ $post->activo ? 'fa-toggle-off' : 'fa-toggle-on' }}"></i>
                                    {{ $post->activo ? 'Desactivar' : 'Activar' }}
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

        <!-- Modal para mostrar comentarios -->
<div id="commentsModal" class="modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; 
    background: rgba(0,0,0,0.5); align-items:center; justify-content:center; z-index:1000;">
  <div style="background:#fff; padding:20px; border-radius:8px; width:90%; max-width:900px; max-height:80vh; overflow-y:auto; position:relative; display:flex; gap:20px;">

    <button id="closeModalBtn" style="position:absolute; top:10px; right:10px; font-size:18px; border:none; background:none; cursor:pointer;">&times;</button>
    <!-- Contenedor info publicación -->
    <div id="publicationInfo" style="flex:1; overflow-y:auto; border-right: 1px solid #ddd; padding-right: 20px;">
      <!-- Aquí se llenará la info de la publicación -->
      <p>Cargando publicación...</p>
    </div>

    <!-- Contenedor comentarios -->
    <div id="commentsContent" style="flex:1; overflow-y:auto; padding-left: 20px;">
      <h3>Comentarios</h3>
      <p>Cargando comentarios...</p>
    </div>
    </div>
  </div>

    </main>
</body>
</html>

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
            const matchesTipo = !filters.tipo || pub.dataset.tipo.toLowerCase() === filters.tipo.toLowerCase();
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

<script>
function confirmToggleStatus(postId) {
    const form = document.getElementById(`toggle-status-form-${postId}`);
    const isActive = form.querySelector('button').classList.contains('status-inactive');
    const actionText = isActive ? 'desactivar' : 'activar';
    
    Swal.fire({
        title: '¿Confirmar acción?',
        text: `Estás a punto de ${actionText} esta publicación`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: `Sí, ${actionText}`,
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Enviar el formulario
            form.submit();
            
            // Mostrar confirmación
            Swal.fire(
                '¡Éxito!',
                `La publicación ha sido ${actionText}da.`,
                'success'
            );
        }
    });
}

// Para mostrar mensajes de sesión (éxito/error)
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '{{ session('success') }}',
        timer: 3000
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}'
    });
@endif
</script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('commentsModal');
  const closeModalBtn = document.getElementById('closeModalBtn');
  const commentsContent = document.getElementById('commentsContent');

  // Cerrar modal
  closeModalBtn.addEventListener('click', () => {
    modal.style.display = 'none';
    commentsContent.innerHTML = '<p>Cargando...</p>';
  });

  // Cerrar modal al click fuera del contenido
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.style.display = 'none';
      commentsContent.innerHTML = '<p>Cargando...</p>';
    }
  });

  // Manejar clic en botones de comentarios
  document.querySelectorAll('.comment-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const postId = btn.getAttribute('data-post-id');

      // Mostrar modal
      modal.style.display = 'flex';

      // Hacer fetch a la ruta show en JSON
      fetch(`/posts/${postId}`, {
        headers: {
          'Accept': 'application/json'
        }
      })
      .then(res => {
        if (!res.ok) throw new Error('Error al cargar la publicación');
        return res.json();
      })
      .then(data => {
        // data debería incluir post, comentarios y likes (ver sugerencia abajo)
        renderCommentsModal(data);
      })
      .catch(err => {
        commentsContent.innerHTML = `<p style="color:red;">${err.message}</p>`;
      });
    });
  });

function renderCommentsModal(data) {
  const post = data.post;
  const comentarios = data.comentarios;

  // Formatear datos
  const fecha = post.fecha ? new Date(post.fecha).toLocaleString() : 'Fecha desconocida';
  const estado = post.activo ? 'Activo' : 'Inactivo';
  const tipo = post.tipo || 'No especificado';

  // Contenedor publicación
  let pubHtml = `
    <h3>Información de la Publicación</h3>
    <h4>${post.contenido}</h4>
    <p><strong>Autor:</strong> ${post.user?.name || post.autorID || 'Anónimo'}</p>
    <p><strong>Fecha:</strong> ${fecha}</p>
    <p><strong>Estado:</strong> ${estado}</p>
    <p><strong>Tipo:</strong> ${tipo}</p>
  `;

  if (post.imagenURL) {
    pubHtml += `<img src="${post.imagenURL}" alt="Imagen publicación" style="max-width:100%; margin-top:10px; border-radius:6px;">`;
  }

  // Contenedor comentarios
  let commentsHtml = '<h3>Comentarios</h3>';

  if (comentarios.length === 0) {
    commentsHtml += `<p>No hay comentarios.</p>`;
  } else {
    comentarios.forEach(c => {
      const fechaC = c.fecha ? new Date(c.fecha).toLocaleString() : 'Fecha desconocida';
      const autor = c.autorNombre || c.autorID || 'Anónimo';
      const texto = c.texto || c.contenido || '';
      commentsHtml += `
        <div style="margin-bottom:10px; border-bottom:1px solid #ddd; padding-bottom:5px;">
          <p><strong>${autor}:</strong> ${texto}</p>
          <small style="color:#666;">${fechaC}</small>
        </div>
      `;
    });
  }

  document.getElementById('publicationInfo').innerHTML = pubHtml;
  document.getElementById('commentsContent').innerHTML = commentsHtml;
}

    });


</script>

<style>
    .filters {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 15px;
    padding: 15px;
    background: #f5f5f5;
    border-radius: 8px;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.filter-group {
    display: flex;
    flex-direction: column;
}

.filter-group label {
    margin-bottom: 5px;
    font-weight: 500;
}

.filter-group select,
.filter-group input {
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.filter-actions {
    grid-column: 1 / -1;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

.no-results-message {
    padding: 20px;
    text-align: center;
    color: #666;
}
</style>