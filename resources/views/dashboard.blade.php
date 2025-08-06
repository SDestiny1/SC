@extends('layouts.app')

@section('content')
    <!-- Contenido principal -->
    <main class="main-content">
        <header class="header">
            <h1>Dashboard Administrativo</h1>
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

        <!-- Widgets principales -->
        <div class="dashboard-widgets">
            <!-- Publicaciones Alumnos -->
            <div class="widget clickable-widget" onclick="window.location.href='{{ route('posts.index') }}'">
                <div class="widget-header">
                    <h3 class="widget-title">Publicaciones por Alumnos</h3>
                    <div class="widget-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                </div>
                <div class="widget-value">{{ $studentPublicationsCount ?? 0}}</div>
                <div class="widget-footer">
                    <i class="fas fa-pencil-alt"></i>
                    <span>Publicaciones activas de alumnos</span>
                </div>
            </div>

            <!-- Publicaciones Maestros -->
            <div class="widget clickable-widget" onclick="window.location.href='{{ route('posts.index') }}'">
                <div class="widget-header">
                    <h3 class="widget-title">Publicaciones por Maestros</h3>
                    <div class="widget-icon">
                        <i class="fas fa-chalkboard-teacher"></i>
                    </div>
                </div>
                <div class="widget-value">{{ $teacherPublicationsCount ?? 0}}</div>
                <div class="widget-footer">
                    <i class="fas fa-book"></i>
                    <span>Publicaciones activas de maestros</span>
                </div>
            </div>

            <!-- Noticias Activas -->
            <div class="widget clickable-widget" onclick="window.location.href='{{ route('noticias.index') }}'">
                <div class="widget-header">
                    <h3 class="widget-title">Noticias Activas</h3>
                    <div class="widget-icon">
                        <i class="fas fa-newspaper"></i>
                    </div>
                </div>
                <div class="widget-value">{{ $activeNewsCount ?? 0}}</div>
                <div class="widget-footer">
                    <i class="fas fa-bullhorn"></i>
                    <span>Noticias activas en el sistema</span>
                </div>
            </div>
        </div>

        <!-- Secciones principales -->
        <div class="main-sections">
            <div class="section">
                <h2 class="section-title">
                    <i class="fas fa-clock"></i>
                    Actividad Reciente
                </h2>
                <table class="activity-table">
                    <thead>
                        <tr>
                            <th>Usuario</th>
                            <th>Contenido</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentActivities as $activity)
                        <tr class="clickable-row" onclick="handleActivityClick('{{ $activity['type'] }}', '{{ $activity['content'] }}')">
                            <td>{{ $activity['user'] }}</td>
                            <td>
                                @if($activity['type'] == 'noticia')
                                    <i class="fas fa-newspaper"></i> Noticia:
                                @else
                                    <i class="fas fa-comment"></i> Publicación:
                                @endif
                                {{ \Illuminate\Support\Str::limit($activity['content'], 50) }}
                            </td>
                            <td>{{ \Carbon\Carbon::parse($activity['date'])->format('d/m/Y H:i') }}</td>
                            <td><span class="status approved">{{ $activity['status'] }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">No hay actividades recientes</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="section">
                <h2 class="section-title">
                    <i class="fas fa-bell"></i>
                    Notificaciones
                </h2>
                <ul class="notification-list">
                    <li class="notification-item clickable-notification" onclick="window.location.href='{{ route('students.index') }}'">
                        <div class="notification-icon">
                            <i class="fas fa-exclamation"></i>
                        </div>
                        <div class="notification-content">
                            <h3 class="notification-title">Nuevas solicitudes</h3>
                            <p class="notification-desc">Tienes 5 nuevas solicitudes de verificación de estudiantes</p>
                            <span class="notification-time">Hace 2 horas</span>
                        </div>
                    </li>
                    <li class="notification-item clickable-notification" onclick="window.location.href='{{ route('calendario.index') }}'">
                        <div class="notification-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="notification-content">
                            <h3 class="notification-title">Evento próximo</h3>
                            <p class="notification-desc">Feria de empleo universitaria comienza en 3 días</p>
                            <span class="notification-time">Ayer</span>
                        </div>
                    </li>
                    <li class="notification-item clickable-notification" onclick="window.location.href='{{ route('dashboard') }}'">
                        <div class="notification-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <div class="notification-content">
                            <h3 class="notification-title">Reporte mensual</h3>
                            <p class="notification-desc">El reporte de actividad de mayo está disponible</p>
                            <span class="notification-time">5 de junio</span>
                        </div>
                    </li>
                </ul>
                
                <h2 class="section-title" style="margin-top: 30px;">
                    <i class="fas fa-bolt"></i>
                    Acciones Rápidas
                </h2>
                <div class="quick-actions">
                    <div class="action-card clickable-action" onclick="window.location.href='{{ route('students.create') }}'">
                        <div class="action-icon">
                            <i class="fas fa-user-plus"></i>
                        </div>
                        <div class="action-title">Nuevo Usuario</div>
                    </div>
                    <div class="action-card clickable-action" onclick="exportData()">
                        <div class="action-icon">
                            <i class="fas fa-file-export"></i>
                        </div>
                        <div class="action-title">Exportar Datos</div>
                    </div>
                    <div class="action-card clickable-action" onclick="window.location.href='{{ route('noticias.create') }}'">
                        <div class="action-icon">
                            <i class="fas fa-bullhorn"></i>
                        </div>
                        <div class="action-title">Nuevo Anuncio</div>
                    </div>
                    <div class="action-card clickable-action" onclick="window.location.href='{{ route('dashboard') }}'">
                        <div class="action-icon">
                            <i class="fas fa-chart-pie"></i>
                        </div>
                        <div class="action-title">Ver Reportes</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráficos/Estadísticas -->
        <div class="section" style="margin-top: 20px;">
            <h2 class="section-title">
                <i class="fas fa-chart-bar"></i>
                Estadísticas de Uso
            </h2>
            <div class="chart-placeholder">
                <p>Gráfico de actividad de usuarios en los últimos 30 días</p>
            </div>
        </div>
    </main>

    <style>
        .clickable-widget {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .clickable-widget:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        }
        
        .clickable-row {
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        
        .clickable-row:hover {
            background-color: rgba(122, 22, 37, 0.1);
        }
        
        .clickable-notification {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .clickable-notification:hover {
            transform: translateX(5px);
            background-color: rgba(122, 22, 37, 0.05);
        }
        
        .clickable-action {
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .clickable-action:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
    </style>

    <script>
        function handleActivityClick(type, content) {
            if (type === 'noticia') {
                window.location.href = '{{ route("noticias.index") }}';
            } else if (type === 'publicacion') {
                window.location.href = '{{ route("posts.index") }}';
            }
        }
        
        function exportData() {
            // Función para exportar datos (puedes implementar la lógica específica)
            alert('Función de exportación en desarrollo');
        }
    </script>
</body>
</html>