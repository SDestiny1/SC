@extends('layouts.app')

@section('title', 'Perfil del Maestro')

@section('content')
<main class="main-content">
    <section class="profile-container">
        <div class="profile-card">
            <div class="profile-header">
                <img src="{{ $teacher->fotoPerfil ?? 'https://randomuser.me/api/portraits/men/32.jpg' }}" alt="Foto de perfil" class="profile-avatar">
                <div class="profile-basic">
                    <h2>{{ $teacher->nombre }} {{ $teacher->apellidoPaterno }} {{ $teacher->apellidoMaterno }}</h2>
                    <p><i class="fas fa-envelope"></i> {{ $teacher->_id }}</p>

                    <p><i class="fas fa-book"></i>
                        @php
                            $materias = $teacher->materias();
                        @endphp
                        @if ($materias->isEmpty())
                            Sin materias asignadas
                        @else
                            {{ $materias->pluck('nombre')->implode(', ') }}
                        @endif
                    </p>

                    <p><i class="fas fa-building"></i> {{ $teacher->grupo?->carrera?->nombreCarrera ?? 'Sin departamento asignado' }}</p>
                </div>
            </div>

            <div class="profile-details">
                <h3>Información General</h3>
                <ul>
                    <li><strong>Departamento:</strong> {{ $teacher->grupo?->carrera?->nombreCarrera ?? 'No asignado' }}</li>
                    <li><strong>Grupo asignado:</strong> {{ $teacher->grupo?->nombreGrupo ?? 'No asignado' }}</li>
                    <li><strong>Estatus:</strong> 
                        <span class="status-badge status-{{ $teacher->activo ? 'active' : 'inactive' }}">
                            {{ $teacher->activo ? 'Activo' : 'Inactivo' }}
                        </span>
                    </li>
                    <li><strong>Fecha de registro:</strong> 
                        @if(is_numeric($teacher->fechaRegistro))
                            {{ \Carbon\Carbon::createFromTimestampMs($teacher->fechaRegistro)->format('d/m/Y') }}
                        @else
                            {{ $teacher->fechaRegistro ? \Carbon\Carbon::parse($teacher->fechaRegistro)->format('d/m/Y') : 'N/A' }}
                        @endif
                    </li>
                </ul>
            </div>

            <div class="profile-details">
                <h3>Horario del Maestro</h3>
                @php
                    $materias = $teacher->materias();
                    $horarios = \App\Models\ClassSchedule::where('clases.docenteID', $teacher->_id)->get();
                    $horarioTabla = [];

                    foreach ($horarios as $horario) {
                        foreach ($horario->clases as $clase) {
                            if ($clase['docenteID'] === $teacher->_id) {
                                $hora = $clase['horaInicio'] . ' - ' . $clase['horaFin'];
                                $dia = ucfirst($horario->diaSemana);
                                $materia = \App\Models\Subject::find($clase['materiaID']);
                                $horarioTabla[$hora][$dia] = $materia?->nombre ?? 'Materia desconocida';
                            }
                        }
                    }

                    $diasSemana = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'];
                    $bloquesHorario = array_keys($horarioTabla);
                @endphp

                @if ($materias->isEmpty() && empty($horarioTabla))
                    <p>Este docente no tiene materias ni horario asignados.</p>

                    @if ($teacher->grupo?->carrera)
                        @if ($materiasDepartamento->isEmpty())
                            <p>No hay materias registradas en el departamento.</p>
                        @else
                        @endif
                    @else
                        <p>El docente no tiene un departamento asignado. No se pueden asignar materias.</p>
                    @endif
                @elseif (!empty($horarioTabla))
                    <div class="overflow-x-auto">
                        <table class="w-full border table-fixed text-sm horario-tabla">
                            <thead>
                                <tr>
                                    <th class="border px-2 py-2 bg-gray-100 w-24">Horario</th>
                                    @foreach ($diasSemana as $dia)
                                        <th class="border px-2 py-2 bg-gray-100">{{ $dia }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($bloquesHorario as $hora)
                                    <tr>
                                        <td class="border px-2 py-2 font-medium bg-gray-50 text-center">{{ $hora }}</td>
                                        @foreach ($diasSemana as $dia)
                                            <td class="border px-2 py-2 text-center align-middle whitespace-normal break-words">
                                                {{ $horarioTabla[$hora][$dia] ?? '-' }}
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p>Este docente no tiene clases registradas.</p>
                @endif
                <div class="profile-actions">
                            <a href="{{ route('teachers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Volver a la lista
                            </a>
                            <button id="openScheduleModal" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Horario
                            </button>
                        </div>
            </div>

            <!-- Modal para asignar horario -->
            <div class="modal" id="scheduleModal" style="display: none;">
                <div class="modal-content" style="width: 80%; max-width: 900px;">
                    <span class="close-modal">&times;</span>
                    <h3>Asignar Horario a {{ $teacher->nombre }}</h3>
                    
                    <form id="assignScheduleForm">
                        @csrf
                        <input type="hidden" name="teacher_id" value="{{ $teacher->_id }}">
                        
                        <div class="form-step" id="step1">
                            <h4>Paso 1: Seleccionar Materia</h4>
                            <div class="form-group">
                                <label for="subject_id">Materia:</label>
                                <select name="subject_id" id="subject_id" class="form-control" required>
                                    <option value="">Seleccione una materia</option>
                                    @foreach($materiasDepartamento as $materia)
                                        <option value="{{ $materia->_id }}">{{ $materia->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="button" class="btn btn-primary next-step">Siguiente</button>
                        </div>
                        
                        <div class="form-step" id="step2" style="display: none;">
                            <h4>Paso 2: Asignar Horarios por Día</h4>
                            
                            <div class="day-schedules">
                                @foreach(['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'] as $day)
                                <div class="day-schedule">
                                    <h5>{{ $day }}</h5>
                                    <div class="time-slots-container" id="{{ strtolower($day) }}_slots"></div>
                                    <button type="button" class="btn btn-sm btn-outline-primary add-time-slot" data-day="{{ strtolower($day) }}">
                                        <i class="fas fa-plus"></i> Agregar horario
                                    </button>
                                </div>
                                @endforeach
                            </div>
                            
                            <div class="form-actions">
                                <button type="button" class="btn btn-secondary prev-step">Anterior</button>
                                <button type="submit" class="btn btn-primary">Guardar Horarios</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</main>

@section('styles')
<style>
    .profile-container {
        display: flex;
        justify-content: center;
        padding: 2rem;
    }

    .profile-card {
        background: #fff;
        border-radius: 1rem;
        padding: 2rem;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        width: 100%;
        max-width: 1000px;
    }

    .profile-header {
        display: flex;
        gap: 1.5rem;
        align-items: center;
        border-bottom: 1px solid #eee;
        padding-bottom: 1rem;
    }

    .profile-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ccc;
    }

    .profile-basic h2 {
        margin: 0;
        font-size: 1.8rem;
    }

    .profile-basic p {
        margin: 0.2rem 0;
        color: #555;
    }

    .profile-details {
        margin-top: 2rem;
    }

    .profile-details h3 {
        margin-bottom: 1rem;
        color: #333;
    }

    .profile-details ul {
        list-style: none;
        padding: 0;
    }

    .profile-details li {
        margin-bottom: 0.5rem;
        font-size: 1rem;
    }

    .status-badge {
        padding: 0.2rem 0.6rem;
        border-radius: 0.5rem;
        font-weight: bold;
        color: #fff;
    }

    .status-active {
        background-color: #28a745;
    }

    .status-inactive {
        background-color: #dc3545;
    }

    .profile-actions {
        margin-top: 2rem;
        display: flex;
        justify-content: space-between;
    }

    .btn {
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        text-decoration: none;
        font-weight: bold;
        transition: background 0.3s ease;
    }

    .btn-secondary {
        background: #6c757d;
        color: #fff;
    }

    .btn-secondary:hover {
        background: #5a6268;
    }

    .btn-primary {
        background: #007bff;
        color: #fff;
    }

    .btn-primary:hover {
        background: #0069d9;
    }

    .btn-outline-primary {
        background: transparent;
        border: 1px solid #007bff;
        color: #007bff;
    }

    .btn-outline-primary:hover {
        background: #007bff;
        color: #fff;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }

    .horario-tabla th,
    .horario-tabla td {
        text-align: center;
        vertical-align: middle;
        white-space: normal;
        word-wrap: break-word;
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1050;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }
    
    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .close-modal {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .close-modal:hover {
        color: black;
    }
    
    .form-step {
        padding: 20px;
    }
    
    .form-group {
        margin-bottom: 15px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }
    
    .form-control {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .form-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
    }

    /* Day schedules */
    .day-schedules {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .day-schedule {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
    
    .day-schedule h5 {
        margin-top: 0;
        margin-bottom: 15px;
        text-align: center;
    }
    
    .time-slot {
        display: flex;
        align-items: center;
        margin-bottom: 10px;
    }
    
    .time-slot select {
        flex: 1;
        margin-right: 5px;
    }
    
    .remove-slot {
        color: #dc3545;
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
        font-size: 1rem;
    }
    
    .add-time-slot {
        width: 100%;
        margin-top: 10px;
    }

    .overflow-x-auto {
        overflow-x: auto;
    }

    .table-fixed {
        table-layout: fixed;
    }

    .w-full {
        width: 100%;
    }

    .border {
        border: 1px solid #dee2e6;
    }

    .bg-gray-100 {
        background-color: #f8f9fa;
    }

    .bg-gray-50 {
        background-color: #f9f9f9;
    }

    .px-2 {
        padding-left: 0.5rem;
        padding-right: 0.5rem;
    }

    .py-2 {
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
    }

    .text-center {
        text-align: center;
    }

    .align-middle {
        vertical-align: middle;
    }

    .whitespace-normal {
        white-space: normal;
    }

    .break-words {
        word-wrap: break-word;
    }

    .w-24 {
        width: 6rem;
    }
</style>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal functionality
    const modal = document.getElementById('scheduleModal');
    const btn = document.getElementById('openScheduleModal');
    const span = document.querySelector('.close-modal');
    
    btn.onclick = function() {
        modal.style.display = 'block';
    }
    
    span.onclick = function() {
        modal.style.display = 'none';
        resetForm();
    }
    
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
            resetForm();
        }
    }
    
    // Form steps navigation
    const nextStepBtn = document.querySelector('.next-step');
    const prevStepBtn = document.querySelector('.prev-step');
    const step1 = document.getElementById('step1');
    const step2 = document.getElementById('step2');
    
    nextStepBtn.onclick = function() {
        const subjectId = document.getElementById('subject_id').value;
        if (!subjectId) {
            alert('Por favor seleccione una materia');
            return;
        }
        
        step1.style.display = 'none';
        step2.style.display = 'block';
    }
    
    prevStepBtn.onclick = function() {
        step2.style.display = 'none';
        step1.style.display = 'block';
    }
    
    // Time slots management
    const timeSlots = [
        '07:00 - 08:00', '08:00 - 09:00', '09:00 - 10:00',
        '10:00 - 11:00', '11:00 - 12:00', '12:00 - 13:00',
        '13:00 - 14:00', '14:00 - 15:00', '15:00 - 16:00',
        '16:00 - 17:00', '17:00 - 18:00'
    ];
    
    document.querySelectorAll('.add-time-slot').forEach(button => {
        button.addEventListener('click', function() {
            const day = this.dataset.day;
            const container = document.getElementById(`${day}_slots`);
            
            const timeSlotDiv = document.createElement('div');
            timeSlotDiv.className = 'time-slot';
            
            const select = document.createElement('select');
            select.name = `schedules[${day}][]`;
            select.className = 'form-control';
            select.required = true;
            
            // Add time slot options
            const emptyOption = document.createElement('option');
            emptyOption.value = '';
            emptyOption.textContent = 'Seleccione horario';
            select.appendChild(emptyOption);
            
            timeSlots.forEach(slot => {
                const option = document.createElement('option');
                option.value = slot;
                option.textContent = slot;
                select.appendChild(option);
            });
            
            const removeBtn = document.createElement('button');
            removeBtn.type = 'button';
            removeBtn.className = 'remove-slot';
            removeBtn.innerHTML = '<i class="fas fa-times"></i>';
            removeBtn.addEventListener('click', function() {
                container.removeChild(timeSlotDiv);
            });
            
            timeSlotDiv.appendChild(select);
            timeSlotDiv.appendChild(removeBtn);
            container.appendChild(timeSlotDiv);
        });
    });
    
    // Form submission
    const form = document.getElementById('assignScheduleForm');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        const schedules = {};
        let hasEmptySlots = false;
        
        // Collect all schedules and validate
        document.querySelectorAll('[name^="schedules["]').forEach(select => {
            const day = select.name.match(/\[(.*?)\]/)[1];
            if (!select.value) {
                hasEmptySlots = true;
                select.style.borderColor = '#dc3545';
            } else {
                if (!schedules[day]) schedules[day] = [];
                schedules[day].push(select.value);
            }
        });
        
        if (hasEmptySlots) {
            alert('Por favor complete todos los horarios seleccionados');
            return;
        }
        
        if (Object.keys(schedules).length === 0) {
            alert('Por favor agregue al menos un horario');
            return;
        }
        
        const payload = {
            teacher_id: formData.get('teacher_id'),
            subject_id: formData.get('subject_id'),
            schedules: schedules
        };
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
        
        fetch('{{ route("teachers.assignSchedule") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(payload)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                modal.style.display = 'none';
                resetForm();
                window.location.reload();
            } else {
                let errorMessage = data.message || 'Error al asignar los horarios';
                if (data.errors) {
                    errorMessage += '\n\n' + Object.values(data.errors).join('\n');
                }
                alert(errorMessage);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Ocurrió un error al procesar la solicitud');
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Guardar Horarios';
        });
    });
    
    function resetForm() {
        document.getElementById('assignScheduleForm').reset();
        document.querySelectorAll('.time-slots-container').forEach(container => {
            container.innerHTML = '';
        });
        step1.style.display = 'block';
        step2.style.display = 'none';
    }
});
</script>
