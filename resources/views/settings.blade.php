@extends('layouts.app') {{-- O el layout que estés usando --}}

@section('title', 'Configuración')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4"><i class="fas fa-cog"></i> Configuración</h1>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('settings.update') }}">
                @csrf
                @method('PUT') {{-- Solo si vas a actualizar datos --}}

                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre de usuario</label>
                    <input type="text" name="nombre" id="nombre" class="form-control" value="{{ old('nombre', auth()->user()->name) }}">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', auth()->user()->email) }}">
                </div>

                <button type="submit" class="btn btn-primary">Guardar cambios</button>
            </form>
        </div>
    </div>
</div>
@endsection
