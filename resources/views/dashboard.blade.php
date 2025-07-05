@extends('layouts.app')

@section('content')
<!-- Elimina el container-fluid o modifícalo así: -->
<div class="container-fluid px-0">
    <!-- Cards Superiores -->
    <div class="row mx-0">
        <div class="col-xl-4 col-md-6 mb-4 px-3">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                ALUMN'S PUBLISH</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">$53,000</div>
                            <div class="mt-2 text-success small">
                                <span class="fas fa-arrow-up"></span>
                                +55% since yesterday
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4 px-3">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                TEACHER'S PUBLISH</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">2,300</div>
                            <div class="mt-2 text-success small">
                                <span class="fas fa-arrow-up"></span>
                                +3% since last week
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4 col-md-6 mb-4 px-3">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                NOTICES</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">+3,462</div>
                            <div class="mt-2 text-danger small">
                                <span class="fas fa-arrow-down"></span>
                                -2% since last quarter
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección Sales Overview -->
    <div class="row mx-0">
        <div class="col-lg-8 mb-4 px-3">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Sales Overview</h6>
                    <div class="text-success small">
                        <span class="fas fa-arrow-up"></span>
                        4% more in 2021
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="salesChart" height="300"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Calendar -->
             <div class="col-lg-4 mb-4 px-3">
            <div class="card shadow mb-4 h-100">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <button id="prevMonth" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <h6 class="m-0 font-weight-bold text-primary" id="calendarTitle">JULIO 2025</h6>
                    <button id="nextMonth" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
                <div class="card-body text-center">
                    <!-- Fecha seleccionada/día actual -->
                    <div id="currentDateDisplay" style="margin-bottom: 20px;">
                        <div style="font-size: 3.5rem; font-weight: bold; color: #4e73df; line-height: 1;" id="currentDay">3</div>
                        <div style="font-size: 1.8rem; color: #5a5c69; font-weight: 500; margin: 5px 0;" id="currentMonth">JULIO</div>
                        <div style="font-size: 1.3rem; color: #858796;" id="currentWeekday">2025 JUEVES</div>
                    </div>
                    
                    <!-- Días de la semana -->
                    <div style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; margin-bottom: 10px;">
                        <div style="text-align: center; font-weight: bold; color: #4e73df; font-size: 0.9rem;">LU</div>
                        <div style="text-align: center; font-weight: bold; color: #4e73df; font-size: 0.9rem;">MA</div>
                        <div style="text-align: center; font-weight: bold; color: #4e73df; font-size: 0.9rem;">MI</div>
                        <div style="text-align: center; font-weight: bold; color: #4e73df; font-size: 0.9rem;">JU</div>
                        <div style="text-align: center; font-weight: bold; color: #4e73df; font-size: 0.9rem;">VI</div>
                        <div style="text-align: center; font-weight: bold; color: #4e73df; font-size: 0.9rem;">SÁ</div>
                        <div style="text-align: center; font-weight: bold; color: #4e73df; font-size: 0.9rem;">DO</div>
                    </div>
                    
                    <!-- Días del mes -->
                    <div id="calendarDays" style="display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sección Get started with Argon -->
    <div class="row mx-0">
        <div class="col-lg-12 mb-4 px-3">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Get started with Argon</h6>
                </div>
                <div class="card-body">
                    <p class="mb-0">There's nothing I really wanted to do in life that I wasn't able to get good at.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configuración del gráfico
        const ctx = document.getElementById('salesChart');
        
        // Verificar si el elemento canvas existe
        if (!ctx) {
            console.error('No se encontró el elemento canvas con ID salesChart');
            return;
        }
        
        // Datos de ejemplo para el gráfico
        const salesData = {
            labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
            datasets: [{
                label: 'Ventas 2023',
                data: [45000, 39000, 52000, 58000, 67000, 73000, 85000, 79000, 88000, 92000, 101000, 115000],
                backgroundColor: 'rgba(78, 115, 223, 0.05)',
                borderColor: 'rgba(78, 115, 223, 1)',
                pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointBorderColor: '#fff',
                pointHoverRadius: 5,
                pointHoverBackgroundColor: 'rgba(78, 115, 223, 1)',
                pointHoverBorderColor: '#fff',
                pointHitRadius: 10,
                pointBorderWidth: 2,
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        };
        
        // Opciones del gráfico
        const options = {
            maintainAspectRatio: false,
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    }
                },
                y: {
                    beginAtZero: false,
                    grid: {
                        color: "rgb(234, 236, 244)",
                        drawBorder: false
                    },
                    ticks: {
                        callback: function(value) {
                            return '$' + value.toLocaleString();
                        }
                    }
                }
            }
        };
        
        // Crear el gráfico
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: salesData,
            options: options
        });
        
        // Manejar redimensionamiento
        window.addEventListener('resize', function() {
            salesChart.resize();
        });

    let currentDate = new Date();
    let currentMonth = currentDate.getMonth();
    let currentYear = currentDate.getFullYear();
    let selectedDate = null;

    // Elementos del DOM
    const calendarTitle = document.getElementById('calendarTitle');
    const prevMonthBtn = document.getElementById('prevMonth');
    const nextMonthBtn = document.getElementById('nextMonth');
    const currentDayElement = document.getElementById('currentDay');
    const currentMonthElement = document.getElementById('currentMonth');
    const currentWeekdayElement = document.getElementById('currentWeekday');
    const calendarDaysElement = document.getElementById('calendarDays');

    // Inicializar calendario
    renderCalendar(currentMonth, currentYear);

    // Event listeners para navegación
    prevMonthBtn.addEventListener('click', function() {
        currentMonth--;
        if (currentMonth < 0) {
            currentMonth = 11;
            currentYear--;
        }
        renderCalendar(currentMonth, currentYear);
    });

    nextMonthBtn.addEventListener('click', function() {
        currentMonth++;
        if (currentMonth > 11) {
            currentMonth = 0;
            currentYear++;
        }
        renderCalendar(currentMonth, currentYear);
    });

    // Función principal para renderizar el calendario
    function renderCalendar(month, year) {
        // Actualizar título
        const monthName = getMonthName(month).toUpperCase();
        calendarTitle.textContent = `${monthName} ${year}`;
        
        // Obtener información del mes
        const firstDay = new Date(year, month, 1).getDay();
        const lastDate = new Date(year, month + 1, 0).getDate();
        const today = new Date();
        
        // Generar días del mes
        let daysHTML = '';
        const offset = firstDay === 0 ? 6 : firstDay - 1; // Ajuste para empezar en lunes
        
        // Días vacíos al inicio
        for (let i = 0; i < offset; i++) {
            daysHTML += '<div style="height: 30px;"></div>';
        }
        
        // Días del mes
        for (let day = 1; day <= lastDate; day++) {
            const isToday = day === today.getDate() && month === today.getMonth() && year === today.getFullYear();
            const isSelected = selectedDate && day === selectedDate.getDate() && month === selectedDate.getMonth() && year === selectedDate.getFullYear();
            
            daysHTML += `
                <div 
                    data-day="${day}"
                    data-month="${month}"
                    data-year="${year}"
                    style="
                        text-align: center; 
                        padding: 5px; 
                        border-radius: 50%; 
                        background-color: ${isSelected ? '#4e73df' : isToday ? '#e9ecef' : 'transparent'};
                        color: ${isSelected ? 'white' : isToday ? '#4e73df' : '#5a5c69'};
                        font-weight: ${(isSelected || isToday) ? 'bold' : 'normal'};
                        cursor: pointer;
                        transition: all 0.2s;
                        height: 30px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        border: ${isToday ? '1px solid #4e73df' : 'none'};
                    ">
                    ${day}
                </div>
            `;
        }
        
        calendarDaysElement.innerHTML = daysHTML;
        
        // Añadir event listeners a los días
        addDayEventListeners();
        
        // Actualizar fecha superior
        updateCurrentDateDisplay(selectedDate || today);
    }
    
    // Añadir event listeners a los días del mes
    function addDayEventListeners() {
        const dayElements = document.querySelectorAll('#calendarDays [data-day]');
        
        dayElements.forEach(dayElement => {
            dayElement.addEventListener('click', function() {
                const day = parseInt(this.getAttribute('data-day'));
                const month = parseInt(this.getAttribute('data-month'));
                const year = parseInt(this.getAttribute('data-year'));
                
                selectedDate = new Date(year, month, day);
                updateCurrentDateDisplay(selectedDate);
                renderCalendar(currentMonth, currentYear); // Vuelve a renderizar para actualizar estilos
            });
            
            // Efectos hover
            dayElement.addEventListener('mouseover', function() {
                if (!this.style.backgroundColor.includes('#4e73df') && !this.style.backgroundColor.includes('#e9ecef')) {
                    this.style.backgroundColor = '#f8f9fe';
                }
            });
            
            dayElement.addEventListener('mouseout', function() {
                const day = parseInt(this.getAttribute('data-day'));
                const month = parseInt(this.getAttribute('data-month'));
                const year = parseInt(this.getAttribute('data-year'));
                const isToday = new Date().getDate() === day && 
                               new Date().getMonth() === month && 
                               new Date().getFullYear() === year;
                const isSelected = selectedDate && 
                                 day === selectedDate.getDate() && 
                                 month === selectedDate.getMonth() && 
                                 year === selectedDate.getFullYear();
                
                if (isSelected) {
                    this.style.backgroundColor = '#4e73df';
                } else if (isToday) {
                    this.style.backgroundColor = '#e9ecef';
                } else {
                    this.style.backgroundColor = 'transparent';
                }
            });
        });
    }
    
    // Actualizar la fecha mostrada arriba
    function updateCurrentDateDisplay(date) {
        currentDayElement.textContent = date.getDate();
        currentMonthElement.textContent = getMonthName(date.getMonth()).toUpperCase();
        currentWeekdayElement.textContent = `${date.getFullYear()} ${getWeekdayName(date).toUpperCase()}`;
    }
    
    // Helper functions
    function getMonthName(month) {
        const months = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
        return months[month];
    }
    
    function getWeekdayName(date) {
        const weekdays = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        return weekdays[date.getDay()];
    }
});
</script>