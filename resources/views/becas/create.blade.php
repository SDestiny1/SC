@extends('layouts.app')

@section('title', 'Crear Nueva Beca/Noticia')

@section('content')
<main class="main-content">
    <header class="header">
        <h1><i class="fas fa-plus-circle"></i> Crear Nueva Beca/Noticia</h1>
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
        <form action="{{ route('becas.store') }}" method="POST">
            @csrf

            <!-- Primera fila: Tipo y Título -->
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="tipo">Tipo</label>
                    <select name="tipo" id="tipo" class="form-control" required>
                        <option value="programa">Programa</option>
                        <option value="beca">Beca</option>
                    </select>
                </div>
                <div class="form-group col-md-9">
                    <label for="titulo">Título</label>
                    <input type="text" name="titulo" id="titulo" class="form-control" required>
                </div>
            </div>

            <!-- Descripción (ocupa fila completa) -->
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="4" required></textarea>
            </div>

            <!-- Segunda fila: Fechas y Datos Institucionales -->
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label for="fechaInicio">Fecha Inicio</label>
                    <input type="date" name="fechaInicio" id="fechaInicio" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="fechaFin">Fecha Fin</label>
                    <input type="date" name="fechaFin" id="fechaFin" class="form-control" required>
                </div>
                <div class="form-group col-md-3">
                    <label for="institucion">Institución</label>
                    <input type="text" name="institucion" id="institucion" class="form-control" required>
                </div>
                <div class="form-group col-md-3" id="monto-container">
                    <label for="monto" id="monto-label">Monto (MXN)</label>
                    <input type="number" step="0.01" name="monto" id="monto" class="form-control">
                </div>
            </div>
 <!-- Tercera fila: Campos específicos según tipo -->
 <div class="form-row" id="campos-beca" style="display: none;">
     <div class="form-group col-md-12">
         <div class="alert alert-info">
             <i class="fas fa-info-circle"></i>
             <strong>Nota:</strong> Para becas, asegúrate de incluir en los requisitos información sobre promedio mínimo y materias reprobadas si aplica.
         </div>
     </div>
 </div>

 <div class="form-row" id="campos-programa" style="display: none;">
     <div class="form-group col-md-12">
         <div class="alert alert-info">
             <i class="fas fa-info-circle"></i>
             <strong>Nota:</strong> Para programas, asegúrate de incluir en los requisitos información sobre inscripción vigente y otros requisitos específicos si aplica.
         </div>
     </div>
 </div>

                          <!-- Campos dinámicos: Requisitos -->
<div class="form-group">
    <label>Requisitos</label>
         <small class="text-muted d-block mb-2" id="requisitos-ejemplos">
         <i class="fas fa-info-circle"></i> 
         <span id="ejemplos-texto">Ejemplos: Ser estudiante regular, Promedio mínimo de 8.0, Sin materias reprobadas, Estar inscrito en el semestre actual, Comprobante de ingresos</span>
     </small>
    <div id="requisitos-container">
        @if(old('requisitos'))
            @foreach(old('requisitos') as $requisito)
                <div class="input-group mb-2">
                    <input type="text" name="requisitos[]" class="form-control" 
                           value="{{ $requisito }}" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-danger remove-field" type="button">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="input-group mb-2">
                <input type="text" name="requisitos[]" class="form-control" 
                       placeholder="Ej: Ser estudiante regular" required>
                <div class="input-group-append">
                    <button class="btn btn-outline-primary add-field" type="button" 
                            data-target="requisitos-container">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Campos dinámicos: Documentos -->
<div class="form-group">
    <label>Documentos Requeridos</label>
    <small class="text-muted d-block mb-2">
        <i class="fas fa-info-circle"></i> 
        Ejemplos: Comprobante de inscripción, Kardex actualizado, Carta de motivos, Identificación oficial, Certificado médico
    </small>
    <div id="documentos-container">
        @if(old('documentos'))
            @foreach(old('documentos') as $documento)
                <div class="input-group mb-2">
                    <input type="text" name="documentos[]" class="form-control" 
                           value="{{ $documento }}" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-danger remove-field" type="button">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        @else
            <div class="input-group mb-2">
                <input type="text" name="documentos[]" class="form-control" 
                       placeholder="Ej: Comprobante de inscripción" required>
                <div class="input-group-append">
                    <button class="btn btn-outline-primary add-field" type="button" 
                            data-target="documentos-container">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </div>
        @endif
         </div>
 </div>
 
 <!-- Condición Especial -->
 <div class="form-row">
     <div class="form-group col-md-12">
         <label for="condicionEspecial">Condición Especial</label>
         <input type="text" name="condicionEspecial" id="condicionEspecial" class="form-control" 
                placeholder="Ej: Solo aplica para alumnos de 5° semestre en adelante">
     </div>
 </div>
 
             <!-- Última fila: URL y Acciones -->
            <div class="form-row">
                <div class="form-group col-md-8">
                    <label for="url">URL Externa <span class="text-danger">*</span></label>
                    <input type="url" name="url" id="url" class="form-control" required 
                           placeholder="https://ejemplo.com/beca">
                </div>
                <div class="form-group col-md-4 align-self-end">
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar
                        </button>
                        <a href="{{ route('becas.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>


@section('scripts')
<script>
    

    document.addEventListener('DOMContentLoaded', function() {
                 // Función para mostrar/ocultar campos según el tipo
         function toggleCampos() {
             const tipo = document.getElementById('tipo').value;
             const camposBeca = document.getElementById('campos-beca');
             const camposPrograma = document.getElementById('campos-programa');
             const montoContainer = document.getElementById('monto-container');
             const montoInput = document.getElementById('monto');
             const ejemplosTexto = document.getElementById('ejemplos-texto');
             
             if (tipo === 'beca') {
                 camposBeca.style.display = 'flex';
                 camposPrograma.style.display = 'none';
                 montoContainer.style.display = 'block';
                 montoInput.required = true;
                 ejemplosTexto.textContent = 'Ejemplos: Ser estudiante regular, Promedio mínimo de 8.0, Sin materias reprobadas, Estar inscrito en el semestre actual, Comprobante de ingresos';
             } else {
                 camposBeca.style.display = 'none';
                 camposPrograma.style.display = 'flex';
                 montoContainer.style.display = 'none';
                 montoInput.required = false;
                 montoInput.value = '';
                 ejemplosTexto.textContent = 'Ejemplos: Ser estudiante regular, Inscripción vigente, Estar inscrito en el semestre actual, Tener disponibilidad de tiempo, Interés en el área de estudio';
             }
         }
        
        // Ejecutar al cargar la página
        toggleCampos();
        
        // Ejecutar cuando cambie el tipo
        document.getElementById('tipo').addEventListener('change', toggleCampos);
        
        // Función genérica para agregar campos
        document.addEventListener('click', function(e) {
            // Agregar campo
            if (e.target.classList.contains('add-field') || e.target.closest('.add-field')) {
                const btn = e.target.classList.contains('add-field') ? e.target : e.target.closest('.add-field');
                const target = btn.getAttribute('data-target');
                const container = document.getElementById(target);
                
                const div = document.createElement('div');
                div.className = 'input-group mb-2';
                
                                 // Placeholders según el tipo de campo
                 let placeholder = '';
                 if (target === 'requisitos-container') {
                     const tipo = document.getElementById('tipo').value;
                     let requisitosEjemplos = [];
                     
                     if (tipo === 'beca') {
                         requisitosEjemplos = [
                             'Ser estudiante regular',
                             'Promedio mínimo de 8.0',
                             'Sin materias reprobadas',
                             'Estar inscrito en el semestre actual',
                             'Tener disponibilidad de tiempo',
                             'Comprobante de ingresos',
                             'Promedio mínimo de 9.0',
                             'No tener materias reprobadas'
                         ];
                     } else {
                         requisitosEjemplos = [
                             'Ser estudiante regular',
                             'Inscripción vigente',
                             'Estar inscrito en el semestre actual',
                             'Tener disponibilidad de tiempo',
                             'Interés en el área de estudio',
                             'Cumplir con el horario del programa',
                             'Asistencia obligatoria',
                             'Compromiso de participación'
                         ];
                     }
                     placeholder = requisitosEjemplos[Math.floor(Math.random() * requisitosEjemplos.length)];
                 } else {
                    const documentosEjemplos = [
                        'Comprobante de inscripción',
                        'Kardex actualizado',
                        'Carta de motivos',
                        'Identificación oficial',
                        'Certificado médico'
                    ];
                    placeholder = documentosEjemplos[Math.floor(Math.random() * documentosEjemplos.length)];
                }
                
                div.innerHTML = `
                    <input type="text" name="${target === 'requisitos-container' ? 'requisitos[]' : 'documentos[]'}" 
                           class="form-control" placeholder="Ej: ${placeholder}" required>
                    <div class="input-group-append">
                        <button class="btn btn-outline-danger remove-field" type="button">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                `;
                container.appendChild(div);
            }
            
            // Eliminar campo
            if (e.target.classList.contains('remove-field') || e.target.closest('.remove-field')) {
                const btn = e.target.classList.contains('remove-field') ? e.target : e.target.closest('.remove-field');
                btn.closest('.input-group').remove();
            }
        });
    });
</script>


@section('styles')
<style>
    .form-container {
        max-width: 1000px;
        margin: 20px auto;
        padding: 25px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    }
     .checkbox-wrapper {
        margin-top: 32px;
    }
    
    .checkbox-wrapper .form-check {
        display: flex;
        align-items: center;
    }
    
    .checkbox-wrapper .form-check-input {
        margin-right: 8px;
        margin-top: 0;
    }
    
    .checkbox-wrapper .form-check-label {
        margin-bottom: 0;
    }
    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -10px 1rem;
    }
    
    .form-group {
        padding: 0 10px;
        margin-bottom: 1.25rem;
        flex: 1 0 0%;
    }
    
    .form-group.col-md-3 { flex: 0 0 25%; max-width: 25%; }
    .form-group.col-md-4 { flex: 0 0 33.333%; max-width: 33.333%; }
    .form-group.col-md-6 { flex: 0 0 50%; max-width: 50%; }
    .form-group.col-md-8 { flex: 0 0 66.666%; max-width: 66.666%; }
    .form-group.col-md-9 { flex: 0 0 75%; max-width: 75%; }
    .form-group.col-md-12 { flex: 0 0 100%; max-width: 100%; }
    
    .align-self-end {
        align-self: flex-end;
        padding-bottom: 0.75rem;
    }
    
    label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        display: block;
        color: #495057;
    }
    
    .form-control {
        border-radius: 6px;
        border: 1px solid #ced4da;
        padding: 10px 15px;
        transition: all 0.3s;
    }
    
    .form-control:focus {
        border-color: #5c9eff;
        box-shadow: 0 0 0 0.2rem rgba(92,158,255,0.25);
    }
    
    textarea.form-control {
        min-height: 120px;
    }
    
    .checkbox-container {
        display: flex;
        align-items: center;
        height: 100%;
        padding-top: 32px;
    }
    
    .checkbox-container .form-check-input {
        margin-right: 8px;
        margin-top: 0;
    }
    
    .checkbox-container .form-check-label {
        margin-bottom: 0;
        font-weight: normal;
    }
    
    .input-group {
        display: flex;
    }
    
    .input-group .form-control {
        flex: 1;
        border-top-right-radius: 0;
        border-bottom-right-radius: 0;
    }
    
    .input-group-append .btn {
        border-top-left-radius: 0;
        border-bottom-left-radius: 0;
        border-left: 0;
    }
    
    .btn {
        padding: 10px 18px;
        border-radius: 6px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.3s;
    }
    
    .btn i {
        font-size: 0.9rem;
    }
    
    .btn-primary {
        background-color: #2c7be5;
        border-color: #2c7be5;
    }
    
    .btn-primary:hover {
        background-color: #2368c8;
        border-color: #2368c8;
    }
    
    .btn-outline-primary {
        color: #2c7be5;
        border-color: #2c7be5;
    }
    
    .btn-outline-primary:hover {
        background-color: #2c7be5;
        color: white;
    }
    
    .btn-outline-secondary {
        color: #6c757d;
        border-color: #dee2e6;
    }
    
    .btn-outline-secondary:hover {
        background-color: #f8f9fa;
    }
    
    .btn-outline-danger {
        color: #e63757;
        border-color: #e63757;
    }
    
    .btn-outline-danger:hover {
        background-color: #e63757;
        color: white;
    }
    
    .form-actions {
        display: flex;
        gap: 10px;
        justify-content: flex-end;
    }
    
    .text-muted {
        color: #6c757d!important;
        font-weight: normal;
    }
    
    /* Estilos para placeholders */
    .form-control::placeholder {
        color: #adb5bd;
        font-style: italic;
        font-size: 0.9rem;
    }
    
    /* Animación para campos dinámicos */
    .input-group {
        animation: fadeIn 0.3s ease-in;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
         /* Estilos para campos ocultos */
     .form-group[style*="display: none"] {
         margin: 0;
         padding: 0;
         height: 0;
         overflow: hidden;
     }
     
     /* Estilos para alertas informativas */
     .alert-info {
         background-color: #d1ecf1;
         border-color: #bee5eb;
         color: #0c5460;
         padding: 12px 16px;
         border-radius: 6px;
         border: 1px solid;
         margin-bottom: 0;
     }
     
     .alert-info i {
         margin-right: 8px;
         color: #17a2b8;
     }
     
     /* Estilos para campos requeridos */
     .text-danger {
         color: #dc3545 !important;
     }
     
     /* Estilos para el grid de detalles */
     .beca-details {
         display: grid;
         grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
         gap: 10px;
         margin-bottom: 15px;
     }
     
     .detail-item {
         display: flex;
         align-items: center;
         gap: 8px;
         font-size: 0.9rem;
         color: #555;
     }
     
     .detail-item i {
         color: #1e5799;
         width: 16px;
     }
</style>
