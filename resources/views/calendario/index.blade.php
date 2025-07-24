@extends('layouts.app')

@section('content')
    <!-- Contenido principal -->
    <main class="main-content">
        <header class="header">
            <h1><i class="fas fa-calendar-alt"></i> Calendario Escolar</h1>
            <div class="user-info">
                <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Usuario">
                <span>María González</span>
            </div>
        </header>

        <div class="calendar-container">
            <!-- Vista de calendario -->
            <div class="calendar-view">
                <div class="calendar-header">
                    <h2 class="calendar-title">Calendario Académico</h2>
                </div>
                
                <div id="calendar"></div>
            </div>
            @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
            
<!-- Formulario de eventos -->
<div class="event-form-container">
    <h3 class="form-title"><i class="fas fa-plus-circle"></i> Agregar Evento/Periodo</h3>
    
    <form action="{{ route('calendario.store') }}" method="POST" id="event-form">
        @csrf
        
        <div class="form-group">
            <label for="event-type">Tipo</label>
            <select id="event-type" name="tipo" class="event-type-selector" required>
                <option value="evento">Evento</option>
                <option value="periodo">Periodo</option>
                <option value="inicio ciclo">Inicio de Ciclo</option>
                <option value="evaluaciones">Evaluaciones</option>
                <option value="día feriado">Día Feriado</option>
            </select>

        </div>
        
        <div class="form-group">
            <label for="event-name">Nombre del Evento/Periodo</label>
            <input type="text" id="event-name" name="nombre" placeholder="Ej: Semana de exámenes parciales" required>
        </div>
        
        <div class="form-group event-date-field">
            <label for="event-date">Día del evento</label>
            <input type="date" id="event-date" name="fecha" required>
        </div>
        
        <div class="form-group period-date-fields" style="display: none;">
            <label>Periodo</label>
            <div class="date-fields">
                <div>
                    <label for="period-start">Inicio</label>
                    <input type="date" id="period-start" name="fecha_inicio">
                </div>
                <div>
                    <label for="period-end">Fin</label>
                    <input type="date" id="period-end" name="fecha_fin">
                </div>
            </div>
        </div>
        
        <div class="form-group">
            <label for="event-description">Descripción (opcional)</label>
            <textarea id="event-description" name="descripcion" placeholder="Agregue una descripción detallada del evento o periodo"></textarea>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Guardar
            </button>
            <button type="reset" class="btn btn-outline">
                <i class="fas fa-times"></i> Cancelar
            </button>
        </div>
    </form>
                
                <!-- Importar desde Excel -->
<form action="{{ route('calendario.importar') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="import-section">
        <h3 class="form-title">
            <i class="fas fa-file-import"></i> Importar desde Excel
        </h3>

        <div class="file-upload" id="file-upload-area" onclick="document.getElementById('archivoExcel').click();">
            <i class="fas fa-file-excel"></i>
            <p>Haz clic para seleccionar tu archivo Excel</p>
            <small>Formatos soportados: .xlsx, .xls, .csv</small>
        </div>

        <input type="file" id="archivoExcel" name="archivo" accept=".xlsx,.xls,.csv" style="display: none;" onchange="this.form.submit()">

        <a class="example-link" id="show-example-btn">
            <i class="fas fa-eye"></i> Ver ejemplo de estructura requerida
        </a>
    </div>
</form>

            </div>
        </div>
    </main>
    
    <!-- Modal de ejemplo Excel -->
    <div class="modal" id="example-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">Estructura requerida para el archivo Excel</h3>
                <button class="close-modal">&times;</button>
            </div>
            
            <p>Para importar correctamente los eventos al calendario escolar, su archivo Excel debe seguir la siguiente estructura:</p>
            
            <table class="example-table">
                <thead>
                    <tr>
                        <th>Tipo</th>
                        <th>Nombre</th>
                        <th>Fecha Evento</th>
                        <th>Fecha Inicio</th>
                        <th>Fecha Fin</th>
                        <th>Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>evento</td>
                        <td>Inicio de clases</td>
                        <td>2024-08-01</td>
                        <td></td>
                        <td></td>
                        <td>Primer día del semestre de otoño</td>
                    </tr>
                    <tr>
                        <td>periodo</td>
                        <td>Vacaciones de invierno</td>
                        <td></td>
                        <td>2024-12-15</td>
                        <td>2025-01-05</td>
                        <td>Receso por fiestas de fin de año</td>
                    </tr>
                    <tr>
                        <td>evento</td>
                        <td>Día del estudiante</td>
                        <td>2024-05-23</td>
                        <td></td>
                        <td></td>
                        <td>Celebración con actividades recreativas</td>
                    </tr>
                    <tr>
                        <td>periodo</td>
                        <td>Exámenes finales</td>
                        <td></td>
                        <td>2024-11-25</td>
                        <td>2024-12-06</td>
                        <td>Periodo de evaluaciones finales</td>
                    </tr>
                </tbody>
            </table>
            
            <div style="margin-top: 20px;">
                <h4>Instrucciones:</h4>
                <ul style="padding-left: 20px; margin-top: 10px;">
                    <li>La columna <strong>Tipo</strong> debe contener "evento" o "periodo"</li>
                    <li>Para eventos: complete solo <strong>Fecha Evento</strong></li>
                    <li>Para periodos: complete <strong>Fecha Inicio</strong> y <strong>Fecha Fin</strong></li>
                    <li>Las fechas deben estar en formato YYYY-MM-DD</li>
                    <li>La primera fila debe contener los encabezados como se muestra</li>
                </ul>
            </div>
            
            <div style="margin-top: 25px; text-align: center;">
                <button class="btn btn-secondary" id="download-template-btn">
                    <i class="fas fa-download"></i> Descargar plantilla
                </button>
            </div>
        </div>
    </div>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>

    <script>
        // Mostrar/ocultar campos según tipo de evento
        const eventTypeSelector = document.querySelector('.event-type-selector');
        const eventDateField = document.querySelector('.event-date-field');
        const periodDateFields = document.querySelector('.period-date-fields');
        
eventTypeSelector.addEventListener('change', function () {
    const tipo = this.value;

    if (tipo === 'evento' || tipo === 'día feriado' || tipo === 'inicio ciclo') {
        eventDateField.style.display = 'block';
        document.getElementById('event-date').setAttribute('required', 'required');

        periodDateFields.style.display = 'none';
        document.getElementById('period-start').removeAttribute('required');
        document.getElementById('period-end').removeAttribute('required');
    } else if (tipo === 'periodo' || tipo === 'evaluaciones') {
        eventDateField.style.display = 'none';
        document.getElementById('event-date').removeAttribute('required');

        periodDateFields.style.display = 'block';
        document.getElementById('period-start').setAttribute('required', 'required');
        document.getElementById('period-end').setAttribute('required', 'required');
    }
});

        // Mostrar modal de ejemplo
        const showExampleBtn = document.getElementById('show-example-btn');
        const exampleModal = document.getElementById('example-modal');
        const closeModal = document.querySelector('.close-modal');
        
        showExampleBtn.addEventListener('click', function() {
            exampleModal.style.display = 'flex';
        });
        
        closeModal.addEventListener('click', function() {
            exampleModal.style.display = 'none';
        });
        
        // Cerrar modal al hacer clic fuera
        exampleModal.addEventListener('click', function(e) {
            if (e.target === exampleModal) {
                exampleModal.style.display = 'none';
            }
        });

        // Inicializar FullCalendar
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'es',  // español
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: @json($eventos ?? []),
                eventDisplay: 'block',
                // Opcional: tooltip con el tipo de evento
                eventDidMount: function(info) {
                    if(info.event.extendedProps.tipoEvento){
                        info.el.setAttribute('title', info.event.extendedProps.tipoEvento);
                    }
                }
            });

            calendar.render();
            document.getElementById('download-template-btn').addEventListener('click', function() {
    window.location.href = "{{ route('plantilla.calendario') }}";
});
        });
    </script>

    

    
</body>
</html>