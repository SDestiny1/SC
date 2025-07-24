@extends('layouts.app')

@section('title', 'Crear Nueva Noticia')

@section('content')
<main class="main-content">
    <h2><i class="fas fa-plus-circle"></i> Crear Nueva Noticia</h2>

    <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="form-news">
        @csrf

        <div class="form-group">
            <label for="titulo">Título</label>
            <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" required>
        </div>

        <div class="form-group">
            <label for="contenido">Contenido</label>
            <textarea name="contenido" id="contenido" rows="5" required>{{ old('contenido') }}</textarea>
        </div>



        <div class="form-group">
            <label for="fechaPublicacion">Fecha de Publicación</label>
            <input type="datetime-local" name="fechaPublicacion" id="fechaPublicacion" value="{{ old('fechaPublicacion') }}" required>
        </div>

        <div class="form-group">
            <label for="activo">¿Activo?</label>
            <select name="activo" id="activo">
                <option value="1" {{ old('activo') == '1' ? 'selected' : '' }}>Sí</option>
                <option value="0" {{ old('activo') == '0' ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="form-group">
            <label for="imagen">Imagen</label>
            <input type="file" name="imagen" id="imagen" accept="image/*" onchange="previewImage(event)">
            <div class="image-preview" style="margin-top: 10px;">
                <img id="preview" src="#" alt="Vista previa" style="max-width: 100%; display: none;">
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Guardar Noticia</button>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</main>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview');
    if (file) {
        preview.src = URL.createObjectURL(file);
        preview.style.display = 'block';
    }
}
</script>

<style>
.form-news {
    max-width: 600px;
    margin: auto;
    padding: 20px;
    background: #fff;
    border-radius: 8px;
}

.form-news .form-group {
    margin-bottom: 15px;
}

.form-news label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

.form-news input,
.form-news textarea,
.form-news select {
    width: 100%;
    padding: 8px;
    border-radius: 5px;
    border: 1px solid #ccc;
}
</style>
