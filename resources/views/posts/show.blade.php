@extends('layouts.app')

@section('title', 'Detalle Publicación')

@section('content')
<main class="main-content" style="display:flex; gap:20px;">

  {{-- Sección principal con datos de la publicación --}}
  <section style="flex: 3; padding: 20px; border: 1px solid #ddd; border-radius: 8px; position: relative;">
    {{-- Botón de regreso --}}
    <a href="{{ route('posts.index') }}" style="position: absolute; top: 10px; left: 10px; text-decoration: none; font-size: 24px; color: #3490dc;">←</a>

    <h2 style="margin-top: 30px;">{{ $post->contenido }}</h2>

    <p style="margin-bottom: 8px;"><strong>Fecha:</strong> {{ $post->fecha_carbon->format('d/m/Y H:i') }}</p>
    <p style="margin-bottom: 8px;"><strong>Estado:</strong> {{ $post->activo ? 'Activo' : 'Inactivo' }}</p>
    <p style="margin-bottom: 8px;"><strong>Visibilidad:</strong> {{ $post->visibilidad ?? 'N/A' }}</p>
    <p style="margin-bottom: 20px;"><strong>Tipo:</strong> {{ $post->tipo ?? 'N/A' }}</p>

    @if($post->imagenURL)
    <img src="{{ asset($post->imagenURL) }}" alt="Imagen de la publicación"
      style="max-width: 700px; width: 100%; height: auto; border-radius: 8px; margin-bottom: 16px;">
    @endif
  </section>

  {{-- Panel lateral con tabs --}}
  <aside style="flex: 2; border: 1px solid #ddd; border-radius: 8px; padding: 15px;">
    <div class="tabs" style="display: flex; border-bottom: 2px solid #ccc; margin-bottom: 10px;">
      <button id="btn-comentarios" class="tab-btn active-tab">Comentarios ({{ $comentarios->count() }})</button>
      <button id="btn-likes" class="tab-btn">Likes ({{ $likes->count() }})</button>
    </div>

    <div id="tab-comentarios" class="tab-content">
      @forelse($comentarios as $comentario)
        <div style="border-bottom: 1px solid #eee; padding: 8px 0;">
          <strong>{{ $comentario['usuarioID'] }}</strong>
          <small>({{ \Carbon\Carbon::createFromTimestampMs($comentario['fecha'])->format('d/m/Y H:i') }})</small>
          <p>{{ $comentario['contenido'] }}</p>
        </div>
      @empty
        <p>No hay comentarios.</p>
      @endforelse
    </div>

    <div id="tab-likes" class="tab-content" style="display:none;">
      @forelse($likes as $like)
        <div style="border-bottom: 1px solid #eee; padding: 8px 0;">
          <strong>{{ $like['usuarioID'] }}</strong>
          <small>({{ \Carbon\Carbon::createFromTimestampMs($like['fecha'])->format('d/m/Y H:i') }})</small>
        </div>
      @empty
        <p>No hay likes.</p>
      @endforelse
    </div>
  </aside>

</main>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const btnComentarios = document.getElementById('btn-comentarios');
    const btnLikes = document.getElementById('btn-likes');
    const tabComentarios = document.getElementById('tab-comentarios');
    const tabLikes = document.getElementById('tab-likes');

    btnComentarios.addEventListener('click', () => {
      btnComentarios.classList.add('active-tab');
      btnLikes.classList.remove('active-tab');
      tabComentarios.style.display = 'block';
      tabLikes.style.display = 'none';
    });

    btnLikes.addEventListener('click', () => {
      btnLikes.classList.add('active-tab');
      btnComentarios.classList.remove('active-tab');
      tabLikes.style.display = 'block';
      tabComentarios.style.display = 'none';
    });
  });
</script>

<style>
  .tab-btn {
    flex: 1;
    background: transparent;
    border: none;
    padding: 10px 0;
    font-size: 16px;
    cursor: pointer;
    border-bottom: 3px solid transparent;
    transition: all 0.3s ease;
  }

  .tab-btn:hover {
    background-color: #f0f0f0;
  }

  .tab-btn.active-tab {
    border-bottom: 3px solid #3490dc;
    font-weight: bold;
    color: #3490dc;
  }

  .tab-content {
    max-height: 450px;
    overflow-y: auto;
    padding-top: 10px;
  }
</style>
