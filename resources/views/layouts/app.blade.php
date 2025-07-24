<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Red Social Estudiantil</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>
<style>
        :root {
            --primary-color: #7A1625; /* Vino/Guinda */
            --secondary-color: #B78E4A; /* Oro/Dorado oscuro */
            --light-color: #FFFFFF; /* Blanco */
            --background-color: #EDEDED; /* Gris claro/Fondo */
            --text-dark: #000000; /* Negro */
            --text-gray: #4A4A4A; /* Gris oscuro */
            --success-color: #2E7D32; /* Verde para indicadores positivos */
            --warning-color: #D84315; /* Naranja/rojo para advertencias */
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        body {
            display: flex;
            min-height: 100vh;
            background-color: var(--background-color);
        }
        
        /* Navegación lateral */
        .sidebar {
            width: 250px;
            background-color: var(--primary-color);
            color: var(--light-color);
            padding: 20px 0;
            height: 100vh;
            position: fixed;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }
        
        .logo {
            text-align: center;
            padding: 0 20px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 20px;
        }
        
        .logo h2 {
            font-size: 1.3rem;
            color: var(--light-color);
        }
        
        .nav-menu {
            list-style: none;
        }
        
        .nav-item {
            margin-bottom: 5px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: var(--light-color);
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.95rem;
        }
        
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255,255,255,0.1);
            border-left: 4px solid var(--secondary-color);
        }
        
        .nav-link i {
            margin-right: 10px;
            font-size: 1.1rem;
        }
        
        /* Contenido principal */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 25px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 1px solid #ddd;
        }
        
        .header h1 {
            color: var(--primary-color);
            font-size: 1.8rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            color: var(--text-gray);
        }
        
        .user-info img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            margin-right: 10px;
        }
        
        /* Widgets del dashboard */
        .dashboard-widgets {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .widget {
            background: var(--light-color);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-top: 4px solid var(--primary-color);
        }
        
        .widget-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .widget-title {
            font-size: 1.1rem;
            color: var(--text-gray);
            font-weight: 600;
        }
        
        .widget-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(183, 142, 74, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--secondary-color);
        }
        
        .widget-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 10px 0;
        }
        
        .widget-footer {
            display: flex;
            align-items: center;
            color: var(--text-gray);
            font-size: 0.9rem;
        }
        
        .widget-footer i {
            margin-right: 5px;
        }
        
        .up {
            color: var(--success-color);
        }
        
        .down {
            color: var(--warning-color);
        }
        
        /* Secciones principales */
        .main-sections {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
        }
        
        .section {
            background: var(--light-color);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-top: 4px solid var(--primary-color);
        }
        
        .section-title {
            font-size: 1.2rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }
        
        .section-title i {
            margin-right: 10px;
            color: var(--secondary-color);
        }
        
        /* Tabla de actividades recientes */
        .activity-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .activity-table th, .activity-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .activity-table th {
            color: var(--text-gray);
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        .activity-table tr:hover {
            background-color: #f9f9f9;
        }
        
        .status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status.pending {
            background-color: #FFF3E0;
            color: #E65100;
        }
        
        .status.approved {
            background-color: #E8F5E9;
            color: var(--success-color);
        }
        
        .status.rejected {
            background-color: #FFEBEE;
            color: var(--warning-color);
        }
        
        /* Lista de notificaciones */
        .notification-list {
            list-style: none;
        }
        
        .notification-item {
            padding: 15px 0;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: flex-start;
        }
        
        .notification-item:last-child {
            border-bottom: none;
        }
        
        .notification-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: rgba(183, 142, 74, 0.1);
            color: var(--secondary-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
        }
        
        .notification-content {
            flex: 1;
        }
        
        .notification-title {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--text-gray);
        }
        
        .notification-desc {
            font-size: 0.9rem;
            color: var(--text-gray);
            margin-bottom: 5px;
        }
        
        .notification-time {
            font-size: 0.8rem;
            color: #95a5a6;
        }
        
        /* Gráfico placeholder */
        .chart-placeholder {
            height: 200px;
            background-color: #f8f9fa;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #bdc3c7;
            margin-top: 20px;
            background-image: linear-gradient(to bottom right, #f5f5f5, #e0e0e0);
        }
        
        /* Quick Actions */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-top: 20px;
        }
        
        .action-card {
            padding: 15px;
            border-radius: 6px;
            background-color: rgba(183, 142, 74, 0.1);
            color: var(--text-gray);
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }
        
        .action-card:hover {
            background-color: rgba(183, 142, 74, 0.2);
            transform: translateY(-3px);
        }
        
        .action-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--secondary-color);
            color: var(--light-color);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
        }
        
        .action-title {
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        /* Barra de herramientas */
        .toolbar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            flex-wrap: wrap;
            gap: 15px;
        }
        
        .search-bar {
            flex: 1;
            min-width: 300px;
            position: relative;
        }
        
        .search-bar input {
            width: 100%;
            padding: 10px 15px 10px 40px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.95rem;
            color: var(--text-gray);
        }
        
        .search-bar i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-gray);
        }
        
        .action-buttons {
            display: flex;
            gap: 10px;
        }
        
        .btn {
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            font-size: 0.9rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: var(--light-color);
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            color: var(--light-color);
        }
        
        .btn-outline {
            background-color: transparent;
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
        }
        
        .btn:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
         /* Filtros */
        .filters {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .filter-group {
            display: flex;
            flex-direction: column;
            min-width: 200px;
        }
        
        .filter-group label {
            margin-bottom: 5px;
            font-size: 0.9rem;
            color: var(--text-gray);
            font-weight: 500;
        }
        
        .filter-group select, .filter-group input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 0.9rem;
            color: var(--text-gray);
        }
        
        /* Tabla de alumnos */
        .students-table-container {
            background-color: var(--light-color);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            overflow-x: auto;
        }
        
        .students-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .students-table th, .students-table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        
        .students-table th {
            background-color: var(--primary-color);
            color: var(--light-color);
            font-weight: 500;
            position: sticky;
            top: 0;
        }
        
        .students-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .students-table tr:hover {
            background-color: #f1f1f1;
        }
        
        .student-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }
        
        .student-name {
            display: flex;
            align-items: center;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            text-align: center;
        }
        
        .status-active {
            background-color: #e8f8f0;
            color: #27ae60;
        }
        
        .status-inactive {
            background-color: #ffebee;
            color: #e74c3c;
        }
        
        .status-pending {
            background-color: #fff4e5;
            color: #f39c12;
        }
        
        .action-icons {
            display: flex;
            gap: 10px;
        }
        
        .action-icon {
            color: var(--text-gray);
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .action-icon:hover {
            color: var(--primary-color);
            transform: scale(1.1);
        }
                /* Paginación */
        .pagination {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
            padding: 15px;
            background-color: var(--light-color);
            border-radius: 0 0 8px 8px;
        }
        
        .pagination-info {
            color: var(--text-gray);
            font-size: 0.9rem;
        }
        
        .pagination-controls {
            display: flex;
            gap: 10px;
        }
        
        .page-btn {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: var(--light-color);
            color: var(--text-gray);
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .page-btn.active {
            background-color: var(--primary-color);
            color: var(--light-color);
            border-color: var(--primary-color);
        }
        
        .page-btn:hover:not(.active) {
            background-color: #f5f5f5;
        }
        /* Contenedor principal */
        .calendar-container {
            display: grid;
            grid-template-columns: 1fr 350px;
            gap: 25px;
        }
        
        /* Formulario de eventos */
        .event-form-container {
            background-color: var(--light-color);
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            height: fit-content;
            position: sticky;
            top: 20px;
        }
        
        .form-title {
            font-size: 1.2rem;
            color: var(--primary-color);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 0.9rem;
            color: var(--text-gray);
            font-weight: 500;
        }
        
        .form-group input, 
        .form-group select, 
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 0.9rem;
            color: var(--text-gray);
        }
        
        .form-group textarea {
            min-height: 100px;
            resize: vertical;
        }
        
        .date-fields {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }
        
        /* Importar Excel */
        .import-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .file-upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 25px;
            border: 2px dashed #ccc;
            border-radius: 8px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .file-upload:hover {
            border-color: var(--primary-color);
            background-color: rgba(122, 22, 37, 0.05);
        }
        
        .file-upload i {
            font-size: 2rem;
            color: var(--secondary-color);
            margin-bottom: 10px;
        }
        
        .file-upload p {
            text-align: center;
            color: var(--text-gray);
            margin-bottom: 5px;
        }
        
        .file-upload small {
            color: #95a5a6;
            font-size: 0.8rem;
        }
        
        .example-link {
            color: var(--secondary-color);
            text-decoration: none;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9rem;
            cursor: pointer;
        }
        
        .example-link:hover {
            text-decoration: underline;
        }
        
        /* Vista de calendario */
        .calendar-view {
            background-color: var(--light-color);
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            height: 880px;
        }
        
        .calendar-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .calendar-title {
            font-size: 1.3rem;
            color: var(--primary-color);
        }
        
        .calendar-nav {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .calendar-nav-btn {
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: var(--light-color);
            color: var(--text-gray);
            cursor: pointer;
        }
        
        .calendar-nav-btn:hover {
            background-color: #f5f5f5;
        }
        
        .current-month {
            font-weight: 500;
            min-width: 150px;
            text-align: center;
        }
        
        .calendar-grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 10px;
        }
        
        .calendar-day-header {
            text-align: center;
            font-weight: 500;
            color: var(--primary-color);
            padding: 8px;
            font-size: 0.9rem;
        }
        
        .calendar-day {
            min-height: 100px;
            border: 1px solid #eee;
            padding: 8px;
            border-radius: 4px;
        }
        
        .day-number {
            font-weight: 500;
            margin-bottom: 5px;
        }
        
        .event-item {
            font-size: 0.75rem;
            padding: 3px 6px;
            background-color: rgba(183, 142, 74, 0.1);
            border-left: 3px solid var(--secondary-color);
            margin-bottom: 4px;
            border-radius: 2px;
            cursor: pointer;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .event-item.period {
            background-color: rgba(122, 22, 37, 0.1);
            border-left-color: var(--primary-color);
        }
        
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 1000;
            justify-content: center;
            align-items: center;
        }
        
        .modal-content {
            background-color: var(--light-color);
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow-y: auto;
            padding: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .modal-title {
            font-size: 1.3rem;
            color: var(--primary-color);
        }
        
        .close-modal {
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-gray);
        }
        
        .example-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        
        .example-table th, .example-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        
        .example-table th {
            background-color: #f5f5f5;
            color: var(--text-gray);
            font-weight: 500;
        }
        
        .example-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        /* Tarjetas de profesores */
        .professor-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 20px;
        }
        
        .professor-card {
            background-color: var(--light-color);
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: transform 0.3s, box-shadow 0.3s;
            border-top: 4px solid var(--primary-color);
        }
        
        .professor-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.12);
        }
        
        .professor-header {
            display: flex;
            align-items: center;
            padding: 20px;
            background: linear-gradient(to right, rgba(122, 22, 37, 0.05), rgba(183, 142, 74, 0.05));
            border-bottom: 1px solid #eee;
        }
        
        .professor-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 15px;
            border: 3px solid var(--secondary-color);
        }
        
        .professor-info {
            flex: 1;
        }
        
        .professor-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 5px;
        }
        
        .professor-title {
            font-size: 0.9rem;
            color: var(--secondary-color);
            font-weight: 500;
        }
        
        .professor-id {
            font-size: 0.85rem;
            color: var(--text-gray);
        }
        
        .professor-details {
            padding: 20px;
        }
        
        .detail-item {
            display: flex;
            margin-bottom: 15px;
            align-items: flex-start;
        }
        
        .detail-icon {
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: rgba(183, 142, 74, 0.1);
            color: var(--secondary-color);
            border-radius: 50%;
            margin-right: 12px;
            flex-shrink: 0;
        }
        
        .detail-content {
            flex: 1;
        }
        
        .detail-label {
            font-size: 0.8rem;
            color: #777;
            margin-bottom: 3px;
        }
        
        .detail-value {
            font-size: 0.95rem;
            color: var(--text-gray);
            font-weight: 500;
        }
        
        .professor-status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        .status-active {
            background-color: rgba(46, 125, 50, 0.1);
            color: var(--success-color);
        }
        
        .status-inactive {
            background-color: rgba(216, 67, 21, 0.1);
            color: var(--warning-color);
        }
        
        .status-onleave {
            background-color: rgba(255, 152, 0, 0.1);
            color: #E65100;
        }
        
        .professor-actions {
            display: flex;
            padding: 15px 20px;
            background-color: #f9f9f9;
            border-top: 1px solid #eee;
            gap: 10px;
        }
        
        .action-btn {
            flex: 1;
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            transition: all 0.3s;
            background-color: rgba(122, 22, 37, 0.05);
            color: var(--primary-color);
            border: 1px solid rgba(122, 22, 37, 0.1);
        }
        
        .action-btn:hover {
            background-color: var(--primary-color);
            color: var(--light-color);
            border-color: var(--primary-color);
        }
        
        .action-btn.secondary {
            background-color: rgba(183, 142, 74, 0.05);
            color: var(--secondary-color);
            border: 1px solid rgba(183, 142, 74, 0.1);
        }
        
        .action-btn.secondary:hover {
            background-color: var(--secondary-color);
            color: var(--light-color);
            border-color: var(--secondary-color);
        }
        
        /* Estadísticas */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background-color: var(--light-color);
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            border-top: 4px solid var(--primary-color);
            display: flex;
            flex-direction: column;
        }
        
        .stat-value {
            font-size: 2.2rem;
            font-weight: 700;
            color: var(--primary-color);
            margin: 10px 0;
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: var(--text-gray);
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .stat-label i {
            color: var(--secondary-color);
        }
        
        .stat-trend {
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 5px;
            margin-top: 5px;
        }
        
        .trend-up {
            color: var(--success-color);
        }
        
        .trend-down {
            color: var(--warning-color);
        }
        /* Publicaciones */
        .publications-container {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
            grid-auto-rows: minmax(150px, auto); /* Altura mínima y automática */
            gap: 20px;
            align-items: start; /* Alineación al inicio */
        }

        .publication {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            padding: 20px;
            border: 1px solid #eee;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            overflow: visible;
            display: flex;
            flex-direction: column;
            height: auto; /* Altura automática */
        }

        /* Estilos para publicaciones con imagen */
        .publication:has(img.publication-image) {
            grid-row: span 2; /* Ocupa más espacio vertical */
        }

        /* Estilos para publicaciones sin imagen */
        .publication:not(:has(img.publication-image)) {
            grid-row: span 1; /* Ocupa menos espacio vertical */
        }
        .publication:hover {
            background-color: #fdfdfd;
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08);
        }

        .publication-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }
        
        .publication-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 5px;
        }
        
        .publication-meta {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 10px;
        }
        
        .publication-author {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .author-avatar {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
        }
        
        .author-name {
            font-size: 0.9rem;
            color: var(--text-gray);
        }
        
        .publication-date {
            font-size: 0.85rem;
            color: #95a5a6;
        }
        
        .publication-content {
            margin-bottom: 15px;
            color: var(--text-gray);
            line-height: 1.5;
        }
        
        .publication-image {
            width: 100%;
            height: auto;
            border-radius: 6px;
            display: block;
            object-fit: cover;
            margin-bottom: 15px;
        }
        .publication-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .publication-stats {
            display: flex;
            gap: 20px;
        }
        
        .stat-item {
            display: flex;
            align-items: center;
            gap: 5px;
            font-size: 0.9rem;
            color: var(--text-gray);
        }
        
        .stat-item i {
            color: var(--secondary-color);
        }
        
        .publication-admin-actions {
            display: flex;
            gap: 10px;
        }
        
        .admin-action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.3s;
            border: none;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        
        .btn-approve {
            background-color: rgba(46, 125, 50, 0.1);
            color: var(--success-color);
        }
        
        .btn-reject {
            background-color: rgba(216, 67, 21, 0.1);
            color: var(--warning-color);
        }
        
        .btn-edit {
            background-color: rgba(183, 142, 74, 0.1);
            color: var(--secondary-color);
        }
        
        .admin-action-btn:hover {
            opacity: 0.8;
        }
        
        .publication-status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            margin-left: 10px;
        }
        
        .status-pending {
            background-color: #FFF3E0;
            color: #E65100;
        }
        
        .status-approved {
            background-color: #E8F5E9;
            color: var(--success-color);
        }
        
        .status-rejected {
            background-color: #FFEBEE;
            color: var(--warning-color);
        }
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 70px;
                overflow: hidden;
            }
            
            .sidebar .logo h2, .sidebar .nav-link span {
                display: none;
            }
            
            .sidebar .nav-link {
                justify-content: center;
                padding: 12px 0;
            }
            
            .sidebar .nav-link i {
                margin-right: 0;
                font-size: 1.3rem;
            }
            
            .main-content {
                margin-left: 70px;
                padding: 15px;
            }
            
            .main-sections {
                grid-template-columns: 1fr;
            }
            
            .header h1 {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>

    <!-- Barra lateral de navegación -->
    <aside class="sidebar">
        <div class="logo">
            <h2>UniSocial Admin</h2>
        </div>
        <ul class="nav-menu">
            <li class="nav-item">
                <a href="{{ route('dashboard') }}" class="nav-link @if(request()->is('dashboard')) active @endif">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('students.index') }}" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Gestión de Alumnos</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('teachers.index') }}" class="nav-link">
                    <i class="fas fa-chalkboard-teacher"></i>
                    <span>Personal Académico</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('calendario.index') }}" class="nav-link">
                    <i class="fas fa-calendar-check"></i>
                    <span>Canlendario Escolar</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('posts.index') }}" class="nav-link">
                    <i class="fas fa-comment-alt"></i>
                    <span>Publicaciones</span>
                </a>
            </li>
                        <li class="nav-item">
                <a href="{{ route('noticias.index') }}" class="nav-link">
                    <i class="fas fa-comment-alt"></i>
                    <span>Noticias</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reportes</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="fas fa-cog"></i>
                    <span>Configuración</span>
                </a>
            </li>
        </ul>
    </aside>


</body>
</html>
