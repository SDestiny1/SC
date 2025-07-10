@extends('layouts.app')

@section('title', 'Manage Posts')

@section('content')
<!-- Contenido principal -->
    <main class="main-content">
        <header class="header">
            <h1><i class="fas fa-comment-alt"></i> Gestión de Publicaciones</h1>
            <div class="user-info">
                <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Usuario">
                <span>María González</span>
            </div>
        </header>

        <!-- Barra de herramientas -->
        <div class="toolbar">
            <div class="search-bar">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Buscar publicaciones por título, autor o contenido...">
            </div>
            <div class="action-buttons">
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    <span>Nueva Publicación</span>
                </button>
                <button class="btn btn-secondary">
                    <i class="fas fa-filter"></i>
                    <span>Filtros</span>
                </button>
            </div>
        </div>

        <!-- Filtros -->
        <div class="filters">
            <div class="filter-group">
                <label for="tipo">Tipo de Publicación</label>
                <select id="tipo">
                    <option value="">Todos los tipos</option>
                    <option value="anuncio">Anuncio Oficial</option>
                    <option value="estudiante">Publicación de Estudiante</option>
                    <option value="profesor">Publicación de Profesor</option>
                    <option value="evento">Evento</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="estado">Estado</label>
                <select id="estado">
                    <option value="">Todos los estados</option>
                    <option value="aprobado">Aprobado</option>
                    <option value="pendiente">Pendiente</option>
                    <option value="rechazado">Rechazado</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="fecha">Fecha de publicación</label>
                <input type="date" id="fecha">
            </div>
            <div class="filter-group">
                <label for="popularidad">Popularidad</label>
                <select id="popularidad">
                    <option value="">Sin filtro</option>
                    <option value="alta">Alta (50+ likes)</option>
                    <option value="media">Media (10-50 likes)</option>
                    <option value="baja">Baja (0-10 likes)</option>
                </select>
            </div>
        </div>

        <!-- Lista de publicaciones -->
        <div class="publications-container">
            <!-- Publicación 1 -->
            <div class="publication">
                <div class="publication-header">
                    <div>
                        <h3 class="publication-title">Bienvenidos al nuevo ciclo escolar <span class="publication-status status-approved">Aprobado</span></h3>
                        <div class="publication-meta">
                            <div class="publication-author">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Autor" class="author-avatar">
                                <span class="author-name">Director Académico</span>
                            </div>
                            <span class="publication-date">15/03/2024</span>
                        </div>
                    </div>
                </div>
                
                <div class="publication-content">
                    Nos complace dar la bienvenida a todos los estudiantes al nuevo ciclo escolar. Este año tenemos preparadas muchas actividades académicas y culturales para enriquecer su experiencia universitaria.
                </div>
                
                <img src="./assets/img/teiou.jpeg" alt="Imagen publicación" class="publication-image">
                
                <div class="publication-actions">
                    <div class="publication-stats">
                        <div class="stat-item">
                            <i class="fas fa-thumbs-up"></i>
                            <span>124</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-comment"></i>
                            <span>23</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-share"></i>
                            <span>15</span>
                        </div>
                    </div>
                    
                    <div class="publication-admin-actions">
                        <button class="admin-action-btn btn-reject">
                            <i class="fas fa-ban"></i> Rechazar
                        </button>
                        <button class="admin-action-btn btn-edit">
                            <i class="fas fa-edit"></i> Eliminar
                        </button>

                    </div>
                </div>
            </div>
            
            <!-- Publicación 2 -->
            <div class="publication">
                <div class="publication-header">
                    <div>
                        <h3 class="publication-title">Guía de Supervivencia para el Primer Cuatrimestre <span class="publication-status status-pending">Pendiente</span></h3>
                        <div class="publication-meta">
                            <div class="publication-author">
                                <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Autor" class="author-avatar">
                                <span class="author-name">Ana López (Estudiante)</span>
                            </div>
                            <span class="publication-date">15/12/2023</span>
                        </div>
                    </div>
                </div>
                
                <div class="publication-content">
                    Como estudiante de segundo año, quiero compartir algunos consejos útiles para los nuevos estudiantes que están comenzando su primer cuatrimestre. Desde cómo organizar tu tiempo hasta los mejores lugares para estudiar en el campus.
                </div>
                
                <img src="./assets/img/services.jpg" alt="Imagen publicación" class="publication-image">
                
                <div class="publication-actions">
                    <div class="publication-stats">
                        <div class="stat-item">
                            <i class="fas fa-thumbs-up"></i>
                            <span>87</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-comment"></i>
                            <span>14</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-share"></i>
                            <span>9</span>
                        </div>
                    </div>
                    
                    <div class="publication-admin-actions">
                        <button class="admin-action-btn btn-reject">
                            <i class="fas fa-ban"></i> Rechazar
                        </button>
                        <button class="admin-action-btn btn-edit">
                            <i class="fas fa-edit"></i> Eliminar
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Publicación 3 -->
            <div class="publication">
                <div class="publication-header">
                    <div>
                        <h3 class="publication-title">Convocatoria para el Torneo de Debate Interuniversitario <span class="publication-status status-approved">Aprobado</span></h3>
                        <div class="publication-meta">
                            <div class="publication-author">
                                <img src="https://randomuser.me/api/portraits/men/75.jpg" alt="Autor" class="author-avatar">
                                <span class="author-name">Prof. Luis Fernández</span>
                            </div>
                            <span class="publication-date">08/03/2024</span>
                        </div>
                    </div>
                </div>
                
                <div class="publication-content">
                    El Departamento de Humanidades invita a todos los estudiantes interesados en participar en el Torneo de Debate Interuniversitario que se llevará a cabo el próximo mes. Inscripciones abiertas hasta el 25 de marzo.
                </div>                
                <div class="publication-actions">
                    <div class="publication-stats">
                        <div class="stat-item">
                            <i class="fas fa-thumbs-up"></i>
                            <span>56</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-comment"></i>
                            <span>7</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-share"></i>
                            <span>12</span>
                        </div>
                    </div>
                    
                    <div class="publication-admin-actions">
                        <button class="admin-action-btn btn-reject">
                            <i class="fas fa-ban"></i> Rechazar
                        </button>
                        <button class="admin-action-btn btn-edit">
                            <i class="fas fa-edit"></i> Eliminar
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Publicación 4 -->
            <div class="publication">
                <div class="publication-header">
                    <div>
                        <h3 class="publication-title">Consejos para el Examen Final de Cálculo <span class="publication-status status-rejected">Rechazado</span></h3>
                        <div class="publication-meta">
                            <div class="publication-author">
                                <img src="https://randomuser.me/api/portraits/men/12.jpg" alt="Autor" class="author-avatar">
                                <span class="author-name">Carlos Méndez (Estudiante)</span>
                            </div>
                            <span class="publication-date">05/03/2024</span>
                        </div>
                    </div>
                </div>
                
                <div class="publication-content">
                    Comparto algunos consejos para prepararse para el examen final de Cálculo Diferencial basados en mi experiencia del semestre pasado. Incluye los temas más importantes y recursos adicionales.
                </div>
                
                <div class="publication-actions">
                    <div class="publication-stats">
                        <div class="stat-item">
                            <i class="fas fa-thumbs-up"></i>
                            <span>42</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-comment"></i>
                            <span>5</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-share"></i>
                            <span>3</span>
                        </div>
                    </div>
                    
                    <div class="publication-admin-actions">
                        <button class="admin-action-btn btn-approve">
                            <i class="fas fa-check"></i> Aprobar
                        </button>
                        <button class="admin-action-btn btn-edit">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                    </div>
                </div>
            </div>
            <!-- Publicación 5 -->
            <div class="publication">
                <div class="publication-header">
                    <div>
                        <h3 class="publication-title">Consejos para el Examen Final de Cálculo <span class="publication-status status-rejected">Rechazado</span></h3>
                        <div class="publication-meta">
                            <div class="publication-author">
                                <img src="https://randomuser.me/api/portraits/men/12.jpg" alt="Autor" class="author-avatar">
                                <span class="author-name">Carlos Méndez (Estudiante)</span>
                            </div>
                            <span class="publication-date">05/03/2024</span>
                        </div>
                    </div>
                </div>
                
                <div class="publication-content">
                    Comparto algunos consejos para prepararse para el examen final de Cálculo Diferencial basados en mi experiencia del semestre pasado. Incluye los temas más importantes y recursos adicionales.
                </div>
                
                <div class="publication-actions">
                    <div class="publication-stats">
                        <div class="stat-item">
                            <i class="fas fa-thumbs-up"></i>
                            <span>42</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-comment"></i>
                            <span>5</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-share"></i>
                            <span>3</span>
                        </div>
                    </div>
                    
                    <div class="publication-admin-actions">
                        <button class="admin-action-btn btn-approve">
                            <i class="fas fa-check"></i> Aprobar
                        </button>
                        <button class="admin-action-btn btn-edit">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                    </div>
                </div>
            </div>
            <!-- Publicación 4 -->
            <div class="publication">
                <div class="publication-header">
                    <div>
                        <h3 class="publication-title">Consejos para el Examen Final de Cálculo <span class="publication-status status-rejected">Rechazado</span></h3>
                        <div class="publication-meta">
                            <div class="publication-author">
                                <img src="https://randomuser.me/api/portraits/men/12.jpg" alt="Autor" class="author-avatar">
                                <span class="author-name">Carlos Méndez (Estudiante)</span>
                            </div>
                            <span class="publication-date">05/03/2024</span>
                        </div>
                    </div>
                </div>
                
                <div class="publication-content">
                    Comparto algunos consejos para prepararse para el examen final de Cálculo Diferencial basados en mi experiencia del semestre pasado. Incluye los temas más importantes y recursos adicionales.
                </div>
                
                <div class="publication-actions">
                    <div class="publication-stats">
                        <div class="stat-item">
                            <i class="fas fa-thumbs-up"></i>
                            <span>42</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-comment"></i>
                            <span>5</span>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-share"></i>
                            <span>3</span>
                        </div>
                    </div>
                    
                    <div class="publication-admin-actions">
                        <button class="admin-action-btn btn-approve">
                            <i class="fas fa-check"></i> Aprobar
                        </button>
                        <button class="admin-action-btn btn-edit">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Paginación -->
        <div class="pagination">
            <button class="page-btn"><i class="fas fa-chevron-left"></i></button>
            <button class="page-btn active">1</button>
            <button class="page-btn">2</button>
            <button class="page-btn">3</button>
            <button class="page-btn">4</button>
            <button class="page-btn">5</button>
            <button class="page-btn"><i class="fas fa-chevron-right"></i></button>
        </div>
    </main>
</body>
</html>
