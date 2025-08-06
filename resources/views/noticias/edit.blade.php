@extends('layouts.app')

@section('title', 'Editar Noticia')

@section('content')
<main class="main-content">
    <h2><i class="fas fa-edit"></i> Editar Noticia</h2>

    <form action="{{ route('noticias.update', $noticia->_id) }}" method="POST" enctype="multipart/form-data" class="form-news">
        @csrf
        @method('PUT')
        
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <label for="titulo">Título *</label>
            <input type="text" name="titulo" id="titulo" value="{{ old('titulo', $noticia->titulo) }}" 
                   class="@error('titulo') is-invalid @enderror" required>
            @error('titulo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="contenido">Contenido *</label>
            <textarea name="contenido" id="contenido" rows="5" 
                      class="@error('contenido') is-invalid @enderror" required>{{ old('contenido', $noticia->contenido) }}</textarea>
            @error('contenido')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="fechaPublicacion">Fecha de Publicación *</label>
            <input type="datetime-local" name="fechaPublicacion" id="fechaPublicacion" 
                   value="{{ old('fechaPublicacion', $noticia->fechaPublicacion->format('Y-m-d\TH:i')) }}" 
                   class="@error('fechaPublicacion') is-invalid @enderror" required>
            @error('fechaPublicacion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="imagen">Imagen</label>
            @if($noticia->imagenURL)
                <div class="current-image">
                    <img src="{{ $noticia->imagenURL }}" alt="Imagen actual" class="current-image-preview">
                    <div class="remove-image-option">
                        <label class="checkbox-label">
                            <input type="checkbox" name="remove_image" value="1"> 
                            <span>Eliminar imagen actual</span>
                        </label>
                    </div>
                </div>
            @endif
            <input type="file" name="imagen" id="imagen" accept="image/*" 
                   class="@error('imagen') is-invalid @enderror"
                   onchange="previewImage(event)">
            @error('imagen')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted">Dejar en blanco para mantener la imagen actual</small>
            <div class="image-preview">
                <img id="preview" src="#" alt="Vista previa" style="max-width: 100%; display: none;">
            </div>
        </div>

        <div class="form-group">
            <label for="activo">Estado</label>
            <select name="activo" id="activo" class="form-control">
                <option value="1" {{ $noticia->activo ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ !$noticia->activo ? 'selected' : '' }}>Inactivo</option>
            </select>
        </div>

        <div class="form-group">
            <div class="button-group">
                <button type="submit" class="btn btn-primary">Actualizar Noticia</button>
                <a href="{{ route('noticias.index') }}" class="btn btn-secondary">Cancelar</a>
            </div>
        </div>
    </form>
</main>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview');
    const fileSize = file.size / 1024 / 1024; // in MB
    const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
    
    if (file && validTypes.includes(file.type)) {
        if (fileSize > 2) {
            alert('La imagen no debe pesar más de 2MB');
            event.target.value = '';
            preview.style.display = 'none';
        } else {
            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        }
    } else {
        alert('Solo se permiten imágenes en formato JPEG, PNG o GIF');
        event.target.value = '';
        preview.style.display = 'none';
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
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
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

.form-news .is-invalid {
    border-color: #dc3545;
}

.form-news .invalid-feedback {
    color: #dc3545;
    font-size: 0.875em;
}

.form-news .form-text {
    font-size: 0.875em;
    color: #6c757d;
}

.btn {
    padding: 10px 20px;
    border-radius: 5px;
    cursor: pointer;
    text-decoration: none;
    display: inline-block;
    margin-right: 10px;
    font-weight: 500;
    transition: all 0.3s ease;
    border: none;
}

.btn-primary {
    background-color: #7A1625;
    color: white;
    border: 1px solid #7A1625;
}

.btn-primary:hover {
    background-color: #5a1120;
    border-color: #5a1120;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(122, 22, 37, 0.3);
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
    border: 1px solid #6c757d;
}

.btn-secondary:hover {
    background-color: #545b62;
    border-color: #545b62;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
}

.button-group {
    display: flex;
    gap: 10px;
    align-items: center;
}

.button-group .btn {
    margin-right: 0;
    flex: 0 0 auto;
}

.current-image {
    margin-bottom: 15px;
    padding: 15px;
    background-color: #f8f9fa;
    border-radius: 8px;
    border: 1px solid #e9ecef;
}

.current-image-preview {
    max-width: 200px;
    max-height: 150px;
    display: block;
    margin-bottom: 15px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.remove-image-option {
    margin-top: 10px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    font-weight: normal;
    cursor: pointer;
    font-size: 14px;
    color: #6c757d;
}

.checkbox-label input[type="checkbox"] {
    margin-right: 8px;
    width: 16px;
    height: 16px;
    cursor: pointer;
}

.checkbox-label span {
    user-select: none;
}

.image-preview {
    margin-top: 15px;
}

.image-preview img {
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>
