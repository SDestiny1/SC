@extends('layouts.app')

@section('content')
    <!-- Contenido principal -->
    <main class="main-content">
        <header class="header">
            <h1><i class="fas fa-calendar-alt"></i> Calendario Escolar</h1>
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
    <script>
    // Agrega esto al inicio de tus scripts
    window.CSRF_TOKEN = '{{ csrf_token() }}';
</script>

    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'es',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth'
        },
        events: @json($eventos ?? []),
        eventDisplay: 'block',
        eventDidMount: function(info) {
            if (info.event.extendedProps.tipoEvento) {
                info.el.setAttribute('title', info.event.extendedProps.tipoEvento);
            }
        },
eventClick: function(info) {
    const evento = info.event;
    const props = evento.extendedProps;

    let isEditable = false;

    const contentHTML = () => {
        const isPeriodo = props.tipoEvento === 'periodo' || props.tipoEvento === 'evaluaciones';
        const showPeriodFields = isPeriodo; // Mostrar campos de periodo siempre si es periodo
        
        return `
            <form id="editar-evento-form" style="text-align: center;">
                <input type="text" id="nombre-evento" value="${evento.title}" ${!isEditable ? 'readonly' : ''} 
                    class="text-center w-full font-bold text-lg mb-2 ${isEditable ? 'border-2 border-orange-300 focus:border-orange-500' : 'border-none bg-transparent'} outline-none" 
                    placeholder="Nombre del evento" />

                <div id="fecha-simple" style="display: ${showPeriodFields ? 'none' : 'block'};">
                    <label style="font-size: 12px; color: #666;">Fecha del evento</label>
                    <input type="date" id="fecha-inicio" value="${evento.startStr}" ${!isEditable ? 'readonly' : ''} 
                        class="text-center w-full text-base bg-transparent mb-2 border-none outline-none" />
                </div>

                <div id="fecha-periodo" style="display: ${showPeriodFields ? 'block' : 'none'};">
                    <div style="margin-bottom: 10px;">
                        <label style="font-size: 12px; color: #666;">Fecha inicio</label>
                        <input type="date" id="fecha-inicio-periodo" value="${evento.startStr}" ${!isEditable ? 'readonly' : ''} 
                            class="text-center w-full text-base bg-transparent mb-2 border-none outline-none" />
                    </div>
                    <div>
                        <label style="font-size: 12px; color: #666;">Fecha fin</label>
                        <input type="date" id="fecha-fin-periodo" value="${evento.endStr || evento.startStr}" ${!isEditable ? 'readonly' : ''} 
                            class="text-center w-full text-base bg-transparent mb-2 border-none outline-none" />
                    </div>
                </div>

                <textarea id="descripcion-evento" ${!isEditable ? 'readonly' : ''} placeholder="Descripción"
                    class="text-center w-full text-base bg-transparent resize-none border-none outline-none">${props.descripcion || ''}</textarea>
            </form>
        `;
    };

    const showModal = (editable = false) => {
        isEditable = editable;

        Swal.fire({
            title: 'Detalles del Evento',
            html: contentHTML(),
            showCancelButton: true,
            confirmButtonText: editable ? 'Guardar' : 'Editar',
            cancelButtonText: editable ? 'Cancelar' : 'Cerrar',
            showDenyButton: !editable,
            denyButtonText: 'Eliminar',
            denyButtonColor: '#dc3545',
            didOpen: () => {
                if (editable) {
                    // Habilitar campos editables
                    document.getElementById('nombre-evento').removeAttribute('readonly');
                    document.getElementById('descripcion-evento').removeAttribute('readonly');
                    
                    // Habilitar campos de fecha según el tipo
                    const fechaInicio = document.getElementById('fecha-inicio');
                    const fechaInicioPeriodo = document.getElementById('fecha-inicio-periodo');
                    const fechaFinPeriodo = document.getElementById('fecha-fin-periodo');
                    
                    if (fechaInicio) fechaInicio.removeAttribute('readonly');
                    if (fechaInicioPeriodo) fechaInicioPeriodo.removeAttribute('readonly');
                    if (fechaFinPeriodo) fechaFinPeriodo.removeAttribute('readonly');
                }
            },
            preConfirm: () => {
                if (editable) {
                    const tipoEvento = props.tipoEvento || 'evento';
                    const isPeriodo = tipoEvento === 'periodo' || tipoEvento === 'evaluaciones';
                    
                    let fechaInicio, fechaFin;
                    
                    if (isPeriodo) {
                        fechaInicio = document.getElementById('fecha-inicio-periodo').value;
                        fechaFin = document.getElementById('fecha-fin-periodo').value;
                    } else {
                        fechaInicio = document.getElementById('fecha-inicio').value;
                        fechaFin = fechaInicio; // Para eventos simples, fecha fin = fecha inicio
                    }
                    
                    const datosEditados = {
                        nombre: document.getElementById('nombre-evento').value,
                        tipoEvento: tipoEvento,
                        fechaInicio: fechaInicio,
                        fechaFin: fechaFin,
                        descripcion: document.getElementById('descripcion-evento').value
                    };
                    
                    const eventIndex = evento.extendedProps.indice;
                    const year = '2025A';
                    
                    return fetch(`/calendario/${year}/${eventIndex}`, {
                        method: 'PATCH',
                        headers: {
                            'X-CSRF-TOKEN': window.CSRF_TOKEN,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(datosEditados)
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(errorData => {
                                throw new Error(errorData.message || 'Error en la respuesta');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            Swal.fire('¡Actualizado!', data.message, 'success');
                            // Actualizar el evento en el calendario
                            evento.setProp('title', datosEditados.nombre);
                            evento.setExtendedProp('tipoEvento', datosEditados.tipoEvento);
                            evento.setExtendedProp('descripcion', datosEditados.descripcion);
                            
                            // Actualizar fechas
                            if (datosEditados.fechaInicio !== datosEditados.fechaFin) {
                                evento.setStart(datosEditados.fechaInicio);
                                evento.setEnd(datosEditados.fechaFin);
                            } else {
                                evento.setStart(datosEditados.fechaInicio);
                                evento.setEnd(null);
                            }
                            
                            return true;
                        } else {
                            throw new Error(data.message);
                        }
                    })
                    .catch(error => {
                        Swal.fire('Error', error.message, 'error');
                        return false;
                    });
                } else {
                    showModal(true);
                }
            },
            preDeny: () => {
    return new Promise((resolve) => {
        Swal.fire({
            title: '¿Eliminar evento?',
            text: "Esta acción no se puede deshacer",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Obtén el índice del evento en el array
                const eventIndex = evento.extendedProps.indice;
                const year = '2025A'; // O obténlo dinámicamente
                
                fetch(`/calendario/${year}/${eventIndex}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': window.CSRF_TOKEN,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error en la respuesta');
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire('¡Eliminado!', data.message, 'success');
                        evento.remove();
                    } else {
                        throw new Error(data.message);
                    }
                    resolve(true);
                })
                .catch(error => {
                    Swal.fire('Error', error.message, 'error');
                    resolve(false);
                });
            } else {
                resolve(false);
            }
        });
    });
}
        });
    };

    showModal(false);
}

    });

    calendar.render();

    // Botón de plantilla Excel
    document.getElementById('download-template-btn').addEventListener('click', function() {
        window.location.href = "{{ route('plantilla.calendario') }}";
    });
});
</script>

<style>
    /* Estilos para el modal de eventos */
    .swal2-popup {
        border-radius: 12px !important;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15) !important;
    }

    .swal2-title {
        color: #2c3e50 !important;
        font-weight: 600 !important;
        font-size: 1.5rem !important;
    }

    .swal2-html-container {
        padding: 1rem 0 !important;
    }

    /* Estilos para los campos de entrada */
    .swal2-html-container input, 
    .swal2-html-container textarea {
        border: 2px solid #e9ecef !important;
        border-radius: 8px !important;
        padding: 12px 16px !important;
        font-size: 14px !important;
        width: 100% !important;
        margin-bottom: 12px !important;
        transition: all 0.3s ease !important;
        background: #ffffff !important;
        text-align: center !important;
        color: #2c3e50 !important;
    }

    .swal2-html-container input:focus, 
    .swal2-html-container textarea:focus {
        border-color: #ff6b35 !important;
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1) !important;
        outline: none !important;
    }

    .swal2-html-container input[readonly], 
    .swal2-html-container textarea[readonly] {
        background-color: #f8f9fa !important;
        color: #6c757d !important;
        cursor: not-allowed !important;
        border-color: #dee2e6 !important;
    }

    /* Estilos para las etiquetas */
    .swal2-html-container label {
        display: block !important;
        font-size: 12px !important;
        color: #6c757d !important;
        font-weight: 600 !important;
        margin-bottom: 6px !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
    }

    /* Estilos para el nombre del evento */
    .swal2-html-container input[id="nombre-evento"] {
        font-size: 18px !important;
        font-weight: 600 !important;
        color: #2c3e50 !important;
        padding: 12px 16px !important;
        margin-bottom: 16px !important;
        border-radius: 8px !important;
        transition: all 0.3s ease !important;
    }

    .swal2-html-container input[id="nombre-evento"][readonly] {
        border: none !important;
        background: transparent !important;
        padding: 8px 0 !important;
    }

    .swal2-html-container input[id="nombre-evento"]:not([readonly]) {
        border: 2px solid #ff6b35 !important;
        background: #ffffff !important;
    }

    .swal2-html-container input[id="nombre-evento"]:not([readonly]):focus {
        border-color: #e55a2b !important;
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1) !important;
    }

    /* Estilos para la descripción */
    .swal2-html-container textarea {
        min-height: 80px !important;
        resize: vertical !important;
        font-family: inherit !important;
    }

    /* Estilos para los campos de fecha */
    .swal2-html-container input[type="date"] {
        text-align: center !important;
        font-weight: 500 !important;
    }

    /* Estilos para los botones */
    .swal2-confirm {
        background: #ff6b35 !important;
        border-color: #ff6b35 !important;
        border-radius: 8px !important;
        padding: 12px 24px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
    }

    .swal2-confirm:hover {
        background: #e55a2b !important;
        border-color: #e55a2b !important;
        transform: translateY(-1px) !important;
    }

    .swal2-cancel {
        background: #6c757d !important;
        border-color: #6c757d !important;
        border-radius: 8px !important;
        padding: 12px 24px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
    }

    .swal2-cancel:hover {
        background: #5a6268 !important;
        border-color: #545b62 !important;
        transform: translateY(-1px) !important;
    }

    .swal2-deny {
        background: #dc3545 !important;
        border-color: #dc3545 !important;
        border-radius: 8px !important;
        padding: 12px 24px !important;
        font-weight: 600 !important;
        transition: all 0.3s ease !important;
    }

    .swal2-deny:hover {
        background: #c82333 !important;
        border-color: #bd2130 !important;
        transform: translateY(-1px) !important;
    }

    /* Estilos para los contenedores de fecha */
    #fecha-simple, #fecha-periodo {
        margin-bottom: 16px !important;
    }

    #fecha-periodo div {
        margin-bottom: 8px !important;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .swal2-html-container input, 
        .swal2-html-container textarea {
            padding: 10px 12px !important;
            font-size: 13px !important;
        }
        
        .swal2-title {
            font-size: 1.3rem !important;
        }
    }
</style>

    

    
</body>
</html>