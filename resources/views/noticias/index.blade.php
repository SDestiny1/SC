@extends('layouts.app')

@section('title', 'Noticias')

@section('content')
<main class="main-content">
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
