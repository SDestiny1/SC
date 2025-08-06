@extends('layouts.app')

@section('title', 'Registrar Nuevo Alumno')

@section('content')
<div class="student-create-container">
    <header class="header">
        <h1><i class="fas fa-user-plus"></i> Registrar Nuevo Alumno</h1>
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

    <div class="form-container">
        <form action="{{ route('students.store') }}" method="POST" class="student-form">
            @csrf
            
            @if($errors->any())
                <div class="alert alert-danger">
                    <h4><i class="fas fa-exclamation-triangle"></i> Errores de validación:</h4>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-sections">
                <!-- Información Personal -->
                <div class="form-section">
                    <h3><i class="fas fa-user"></i> Información Personal</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nombre">Nombre(s) *</label>
                            <input type="text" name="nombre" id="nombre" 
                                   value="{{ old('nombre') }}" 
                                   class="@error('nombre') is-invalid @enderror" required>
                            @error('nombre')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="apellidoPaterno">Apellido Paterno *</label>
                            <input type="text" name="apellidoPaterno" id="apellidoPaterno" 
                                   value="{{ old('apellidoPaterno') }}" 
                                   class="@error('apellidoPaterno') is-invalid @enderror" required>
                            @error('apellidoPaterno')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="apellidoMaterno">Apellido Materno *</label>
                            <input type="text" name="apellidoMaterno" id="apellidoMaterno" 
                                   value="{{ old('apellidoMaterno') }}" 
                                   class="@error('apellidoMaterno') is-invalid @enderror" required>
                            @error('apellidoMaterno')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="fechaNacimiento">Fecha de Nacimiento *</label>
                            <input type="date" name="fechaNacimiento" id="fechaNacimiento" 
                                   value="{{ old('fechaNacimiento') }}" 
                                   class="@error('fechaNacimiento') is-invalid @enderror" required>
                            @error('fechaNacimiento')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="genero">Género</label>
                            <select name="genero" id="genero" class="@error('genero') is-invalid @enderror">
                                <option value="">Seleccionar género</option>
                                <option value="masculino" {{ old('genero') == 'masculino' ? 'selected' : '' }}>Masculino</option>
                                <option value="femenino" {{ old('genero') == 'femenino' ? 'selected' : '' }}>Femenino</option>
                                <option value="otro" {{ old('genero') == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('genero')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Información Académica -->
                <div class="form-section">
                    <h3><i class="fas fa-graduation-cap"></i> Información Académica</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="grupoID">Grupo *</label>
                            <select name="grupoID" id="grupoID" 
                                    class="@error('grupoID') is-invalid @enderror" required>
                                <option value="">Seleccionar grupo</option>
                                @if(isset($groups) && $groups->count() > 0)
                                    @foreach($groups as $group)
                                        <option value="{{ $group->_id }}" 
                                                {{ old('grupoID') == $group->_id ? 'selected' : '' }}>
                                            {{ $group->nombre }} - {{ $group->carrera->nombreCarrera ?? 'Sin carrera' }} 
                                            ({{ $group->semestre }}° Semestre)
                                        </option>
                                    @endforeach
                                @else
                                    <option value="" disabled>No hay grupos disponibles</option>
                                @endif
                            </select>
                            @error('grupoID')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="matricula">Matrícula</label>
                            <input type="text" name="matricula" id="matricula" 
                                   value="{{ old('matricula') }}" 
                                   class="@error('matricula') is-invalid @enderror"
                                   placeholder="Opcional - se generará automáticamente">
                            @error('matricula')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Información de Contacto -->
                <div class="form-section">
                    <h3><i class="fas fa-envelope"></i> Información de Contacto</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="_id">Correo Electrónico *</label>
                            <input type="email" name="_id" id="_id" 
                                   value="{{ old('_id') }}" 
                                   class="@error('_id') is-invalid @enderror" required>
                            @error('_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text">Este será el correo de acceso al sistema</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="telefono">Teléfono</label>
                            <input type="tel" name="telefono" id="telefono" 
                                   value="{{ old('telefono') }}" 
                                   class="@error('telefono') is-invalid @enderror"
                                   placeholder="Opcional">
                            @error('telefono')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Información de Seguridad -->
                <div class="form-section">
                    <h3><i class="fas fa-lock"></i> Información de Seguridad</h3>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Contraseña *</label>
                            <div class="password-input">
                                <input type="password" name="password" id="password" 
                                       class="@error('password') is-invalid @enderror" required>
                                <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text">Mínimo 8 caracteres</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirmation">Confirmar Contraseña *</label>
                            <div class="password-input">
                                <input type="password" name="password_confirmation" id="password_confirmation" 
                                       class="@error('password_confirmation') is-invalid @enderror" required>
                                <button type="button" class="toggle-password" onclick="togglePassword('password_confirmation')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                            @error('password_confirmation')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Registrar Alumno
                </button>
                <a href="{{ route('students.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const button = input.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
    }
}

// Validación de contraseñas
document.getElementById('password_confirmation').addEventListener('input', function() {
    const password = document.getElementById('password').value;
    const confirmation = this.value;
    
    if (password !== confirmation) {
        this.setCustomValidity('Las contraseñas no coinciden');
    } else {
        this.setCustomValidity('');
    }
});

// Validación de fecha de nacimiento
document.getElementById('fechaNacimiento').addEventListener('change', function() {
    const birthDate = new Date(this.value);
    const today = new Date();
    const age = today.getFullYear() - birthDate.getFullYear();
    
    if (age < 15 || age > 100) {
        this.setCustomValidity('La edad debe estar entre 15 y 100 años');
    } else {
        this.setCustomValidity('');
    }
});
</script>

<style>
.student-create-container {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.header h1 {
    font-size: 2rem;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}

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
    font-weight: 500;
}

.form-container {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.student-form {
    padding: 30px;
}

.form-sections {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

.form-section {
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    padding: 25px;
    background: #fafafa;
}

.form-section h3 {
    margin: 0 0 20px 0;
    color: #374151;
    font-size: 1.25rem;
    display: flex;
    align-items: center;
    gap: 10px;
    padding-bottom: 10px;
    border-bottom: 2px solid #7A1625;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    color: #374151;
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.form-group input,
.form-group select {
    padding: 12px 16px;
    border: 2px solid #d1d5db;
    border-radius: 6px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: #fff;
}

.form-group input:focus,
.form-group select:focus {
    outline: none;
    border-color: #7A1625;
    box-shadow: 0 0 0 3px rgba(122, 22, 37, 0.1);
}

.form-group .is-invalid {
    border-color: #dc3545;
}

.form-group .invalid-feedback {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 5px;
}

.form-group .form-text {
    color: #6b7280;
    font-size: 0.875rem;
    margin-top: 5px;
}

.password-input {
    position: relative;
    display: flex;
    align-items: center;
}

.password-input input {
    flex: 1;
    padding-right: 50px;
}

.toggle-password {
    position: absolute;
    right: 12px;
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    padding: 5px;
    border-radius: 4px;
    transition: color 0.3s ease;
}

.toggle-password:hover {
    color: #7A1625;
}

.alert {
    padding: 15px 20px;
    border-radius: 8px;
    margin-bottom: 25px;
    border-left: 4px solid;
}

.alert-danger {
    background-color: #fef2f2;
    border-color: #dc3545;
    color: #dc3545;
}

.alert h4 {
    margin: 0 0 10px 0;
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.alert ul {
    margin: 0;
    padding-left: 20px;
}

.alert li {
    margin-bottom: 5px;
}

.form-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 30px;
    padding-top: 25px;
    border-top: 1px solid #e5e7eb;
}

.btn {
    padding: 12px 24px;
    border-radius: 6px;
    font-size: 1rem;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    border: none;
    transition: all 0.3s ease;
}

.btn-primary {
    background-color: #7A1625;
    color: white;
}

.btn-primary:hover {
    background-color: #5a1120;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(122, 22, 37, 0.3);
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background-color: #545b62;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    .header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .student-form {
        padding: 20px;
    }
}
</style>
@endsection 