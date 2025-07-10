@extends('layouts.app') {{-- O el layout que estés usando --}}

@section('title', 'Calendario Escolar')

@section('content')
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
                    <div class="calendar-nav">
                        <button class="calendar-nav-btn"><i class="fas fa-chevron-left"></i></button>
                        <span class="current-month">Mayo 2024</span>
                        <button class="calendar-nav-btn"><i class="fas fa-chevron-right"></i></button>
                    </div>
                </div>
                
                <div class="calendar-grid">
                    <div class="calendar-day-header">Lun</div>
                    <div class="calendar-day-header">Mar</div>
                    <div class="calendar-day-header">Mié</div>
                    <div class="calendar-day-header">Jue</div>
                    <div class="calendar-day-header">Vie</div>
                    <div class="calendar-day-header">Sáb</div>
                    <div class="calendar-day-header">Dom</div>
                    
                    <!-- Días del calendario (ejemplo) -->
                    <div class="calendar-day">
                        <div class="day-number">29</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">30</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">1</div>
                        <div class="event-item">Inicio de clases</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">2</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">3</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">4</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">5</div>
                    </div>
                    
                    <!-- Segunda semana -->
                    <div class="calendar-day">
                        <div class="day-number">6</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">7</div>
                        <div class="event-item period">Semana de exámenes</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">8</div>
                        <div class="event-item period">Semana de exámenes</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">9</div>
                        <div class="event-item period">Semana de exámenes</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">10</div>
                        <div class="event-item period">Semana de exámenes</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">11</div>
                        <div class="event-item period">Semana de exámenes</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">12</div>
                    </div>
                    
                    <!-- Tercera semana -->
                    <div class="calendar-day">
                        <div class="day-number">13</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">14</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">15</div>
                        <div class="event-item">Feria de empleo</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">16</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">17</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">18</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">19</div>
                    </div>
                    
                    <!-- Cuarta semana -->
                    <div class="calendar-day">
                        <div class="day-number">20</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">21</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">22</div>
                        <div class="event-item">Día del estudiante</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">23</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">24</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">25</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">26</div>
                    </div>
                    
                    <!-- Quinta semana -->
                    <div class="calendar-day">
                        <div class="day-number">27</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">28</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">29</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">30</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">31</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">1</div>
                    </div>
                    <div class="calendar-day">
                        <div class="day-number">2</div>
                    </div>
                </div>
            </div>
            
            <!-- Formulario de eventos -->
            <div class="event-form-container">
                <h3 class="form-title"><i class="fas fa-plus-circle"></i> Agregar Evento/Periodo</h3>
                
                <div class="form-group">
                    <label for="event-type">Tipo</label>
                    <select id="event-type" class="event-type-selector">
                        <option value="event">Evento</option>
                        <option value="period">Periodo</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="event-name">Nombre del Evento/Periodo</label>
                    <input type="text" id="event-name" placeholder="Ej: Semana de exámenes parciales">
                </div>
                
                <div class="form-group event-date-field">
                    <label for="event-date">Día del evento</label>
                    <input type="date" id="event-date">
                </div>
                
                <div class="form-group period-date-fields" style="display: none;">
                    <label>Periodo</label>
                    <div class="date-fields">
                        <div>
                            <label for="period-start">Inicio</label>
                            <input type="date" id="period-start">
                        </div>
                        <div>
                            <label for="period-end">Fin</label>
                            <input type="date" id="period-end">
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="event-description">Descripción (opcional)</label>
                    <textarea id="event-description" placeholder="Agregue una descripción detallada del evento o periodo"></textarea>
                </div>
                
                <div class="form-actions">
                    <button class="btn btn-primary">
                        <i class="fas fa-save"></i> Guardar
                    </button>
                    <button class="btn btn-outline">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                </div>
                
                <!-- Importar desde Excel -->
                <div class="import-section">
                    <h3 class="form-title"><i class="fas fa-file-import"></i> Importar desde Excel</h3>
                    
                    <div class="file-upload" id="file-upload-area">
                        <i class="fas fa-file-excel"></i>
                        <p>Arrastra tu archivo Excel aquí o haz clic para seleccionar</p>
                        <small>Formatos soportados: .xlsx, .xls, .csv</small>
                    </div>
                    
                    <a class="example-link" id="show-example-btn">
                        <i class="fas fa-eye"></i> Ver ejemplo de estructura requerida
                    </a>
                </div>
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

    <script>
        // Mostrar/ocultar campos según tipo de evento
        const eventTypeSelector = document.querySelector('.event-type-selector');
        const eventDateField = document.querySelector('.event-date-field');
        const periodDateFields = document.querySelector('.period-date-fields');
        
        eventTypeSelector.addEventListener('change', function() {
            if (this.value === 'event') {
                eventDateField.style.display = 'block';
                periodDateFields.style.display = 'none';
            } else {
                eventDateField.style.display = 'none';
                periodDateFields.style.display = 'block';
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
        
        // Simular descarga de plantilla
        document.getElementById('download-template-btn').addEventListener('click', function() {
            alert('Plantilla descargada (simulación)');
            exampleModal.style.display = 'none';
        });
        
        // Simular subida de archivo
        document.getElementById('file-upload-area').addEventListener('click', function() {
            // En una implementación real, esto abriría un diálogo de selección de archivos
            alert('Diálogo de selección de archivo abierto (simulación)');
        });
    </script>
</body>
</html>