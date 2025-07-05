<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Dashboard SC') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles -->
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-bg: #2a3f54;
            --sidebar-color: #fff;
            --topbar-height: 60px;
        }
        
        body {
            background-color: #f8f9fc;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        .chart-area {
            position: relative;
            height: 300px;
            width: 100%;
        }

        @media (min-width: 768px) {
            .chart-area {
                height: 350px;
            }
        }
        .like-btn.liked i,
    .dislike-btn.disliked i {
        animation: pop 1s ease-out;
        color:rgb(0, 0, 0);
    }

    .dislike-btn.disliked i {
        color: #dc3545;
    }


    @keyframes pop {
        0% { transform: scale(1); }
        50% { transform: scale(1.3); }
        100% { transform: scale(1); }
    }
        #sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            color: var(--sidebar-color);
            z-index: 1000;
        }
        
        #topbar {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            height: var(--topbar-height);
            background: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 999;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .main-content {
            position: relative;
            margin-left: var(--sidebar-width);
            margin-top: var(--topbar-height);
            padding: 20px;
            width: calc(100vw - var(--sidebar-width));
            min-height: calc(100vh - var(--topbar-height));
        }
        
        /* Eliminar todos los límites de ancho */
        .container-fluid {
            width: 100%;
            padding: 0;
            margin: 0;
            max-width: 100%;
        }
        
        .full-width-row {
            width: 100vw;
            margin-left: calc(-1 * (100vw - 100%));
            padding: 0 20px;
        }
        
        .nav-link {
            color: var(--sidebar-color);
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 0;
            display: flex;
            align-items: center;
        }
        
        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
        }
        
        .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .app-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: #333;
        }
        
        .card {
            border-radius: 0.35rem;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            margin-bottom: 20px;
        }
        
        /* Ajustes específicos para el dashboard */
        .dashboard-card {
            padding: 15px;
        }
        
        .stat-value {
            font-size: 1.75rem;
            font-weight: bold;
        }
        
        .stat-change.positive {
            color: #28a745;
        }
        
        .stat-change.negative {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div id="sidebar">
        <div class="d-flex flex-column h-100">
            <div class="p-3 text-center">
                <h4 class="mb-0">MENÚ</h4>
            </div>
            
            <nav class="flex-grow-1">
                <ul class="nav flex-column">
                    <li class="nav-item">
                                <li class="nav-item">
                                <a class="nav-link active" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                                <ul class="nav flex-column ps-4">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('students.index') }}">
                                            <i class="fas fa-user-graduate"></i> Alumnos
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('teachers.index') }}">
                                            <i class="fas fa-chalkboard-teacher"></i> Maestros
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('posts.index') }}">
                                            <i class="fas fa-newspaper"></i> Publicaciones
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('groups.index') }}">
                                            <i class="fas fa-users"></i> Grupos
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('settings') }}">
                                    <i class="fas fa-cog"></i> Configuración
                                </a>
                                <ul class="nav flex-column ps-4">
                                    <li class="nav-item">
                                        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                            <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
    
    <!-- Topbar -->
    <div id="topbar">
        <div class="app-name">Dashboard SC</div>
    </div>
    
    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>