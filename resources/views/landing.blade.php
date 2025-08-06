<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>SchoolConnect - Red Social Estudiantil</title>
  <meta name="description" content="Plataforma integral de gestión estudiantil que conecta estudiantes, docentes y administradores en un entorno digital colaborativo">
  <meta name="keywords" content="red social estudiantil, gestión académica, comunicación escolar, plataforma educativa">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Raleway:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="assets/vendor/aos/aos.css" rel="stylesheet">
  <link href="assets/vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="assets/css/main.css" rel="stylesheet">
</head>

<body class="index-page">

  <header id="header" class="header d-flex align-items-center fixed-top">
    <div class="container-fluid container-xl position-relative d-flex align-items-center">

      <a href="#" class="logo d-flex align-items-center me-auto">
        <img src="assets/img/logo-icon-blanco.png" alt="SchoolConnect Logo">
        <h1 class="sitename">SchoolConnect</h1>
      </a>

      <nav id="navmenu" class="navmenu">
        <ul>
          <li><a href="#hero" class="active">Inicio</a></li>
          <li><a href="#about">Acerca de</a></li>
          <li><a href="#features">Características</a></li>
          <li><a href="#tools">Herramientas</a></li>
          <li><a href="#contact">Contacto</a></li>
        </ul>
        <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
      </nav>

      <a class="cta-btn" href="{{ route('login') }}">Iniciar Sesión</a>

    </div>
  </header>

  <main class="main">

    <!-- Hero Section -->
    <section id="hero" class="hero section dark-background">

      <img src="assets/img/hero-bg.jpg" alt="" data-aos="fade-in">

      <div class="container d-flex flex-column align-items-center">
        <h2 data-aos="fade-up" data-aos-delay="100">CONECTA. APRENDE. CRECE.</h2>
        <p data-aos="fade-up" data-aos-delay="200">La plataforma integral que revoluciona la gestión estudiantil y conecta a toda la comunidad educativa</p>
        <div class="d-flex mt-4" data-aos="fade-up" data-aos-delay="300">
          <a href="#features" class="btn-get-started">Descubre Más</a>
        </div>
      </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

      <div class="container">

        <div class="row gy-4">
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
            <h3>Revoluciona la Gestión Estudiantil</h3>
            <img src="assets/img/about.jpg" class="img-fluid rounded-4 mb-4" alt="Gestión Estudiantil">
            <p>SchoolConnect es una plataforma integral diseñada específicamente para instituciones educativas que buscan modernizar su gestión estudiantil y mejorar la comunicación entre todos los miembros de la comunidad educativa.</p>
            <p>Nuestra solución combina las mejores prácticas de redes sociales con herramientas administrativas avanzadas, creando un entorno digital que facilita la colaboración, el aprendizaje y la gestión eficiente de la información académica.</p>
          </div>
          <div class="col-lg-6" data-aos="fade-up" data-aos-delay="250">
            <div class="content ps-0 ps-lg-5">
              <p class="fst-italic">
                "Transformamos la manera en que las instituciones educativas gestionan su información y conectan con su comunidad"
              </p>
              <ul>
                <li><i class="bi bi-check-circle-fill"></i> <span>Gestión integral de estudiantes y docentes</span></li>
                <li><i class="bi bi-check-circle-fill"></i> <span>Sistema de publicaciones y comunicación en tiempo real</span></li>
                <li><i class="bi bi-check-circle-fill"></i> <span>Calendario académico interactivo y gestión de noticias</span></li>
                <li><i class="bi bi-check-circle-fill"></i> <span>Control de becas y seguimiento académico</span></li>
                <li><i class="bi bi-check-circle-fill"></i> <span>Interfaz intuitiva y responsive para todos los dispositivos</span></li>
              </ul>
              <p>
                Desarrollado por Corvus Byte, nuestro equipo especializado en soluciones tecnológicas educativas garantiza una implementación exitosa y soporte continuo para tu institución.
              </p>

              <div class="position-relative mt-4">
                <img src="assets/img/about-2.jpg" class="img-fluid rounded-4" alt="Plataforma Educativa">
                <a href="https://www.youtube.com/watch?v=Y7f98aduVJ8" class="glightbox pulsating-play-btn"></a>
              </div>
            </div>
          </div>
        </div>

      </div>

    </section><!-- /About Section -->

    <!-- Stats Section -->
    <section id="stats" class="stats section light-background">

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">

          <div class="col-lg-3 col-md-6">
            <div class="stats-item d-flex align-items-center w-100 h-100">
              <i class="bi bi-people color-blue flex-shrink-0"></i>
              <div>
                <span data-purecounter-start="0" data-purecounter-end="1000" data-purecounter-duration="1" class="purecounter"></span>
                <p>Estudiantes Conectados</p>
              </div>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6">
            <div class="stats-item d-flex align-items-center w-100 h-100">
              <i class="bi bi-person-workspace color-orange flex-shrink-0"></i>
              <div>
                <span data-purecounter-start="0" data-purecounter-end="150" data-purecounter-duration="1" class="purecounter"></span>
                <p>Docentes Activos</p>
              </div>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6">
            <div class="stats-item d-flex align-items-center w-100 h-100">
              <i class="bi bi-calendar-event color-green flex-shrink-0"></i>
              <div>
                <span data-purecounter-start="0" data-purecounter-end="500" data-purecounter-duration="1" class="purecounter"></span>
                <p>Eventos Programados</p>
              </div>
            </div>
          </div><!-- End Stats Item -->

          <div class="col-lg-3 col-md-6">
            <div class="stats-item d-flex align-items-center w-100 h-100">
              <i class="bi bi-award color-pink flex-shrink-0"></i>
              <div>
                <span data-purecounter-start="0" data-purecounter-end="50" data-purecounter-duration="1" class="purecounter"></span>
                <p>Becas Gestionadas</p>
              </div>
            </div>
          </div><!-- End Stats Item -->

        </div>

      </div>

    </section><!-- /Stats Section -->

    <!-- Features Section -->
    <section id="features" class="services section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Características Principales</h2>
        <p>Descubre las herramientas que revolucionarán tu gestión educativa</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-5">

          <div class="col-xl-4 col-md-6" data-aos="zoom-in" data-aos-delay="200">
            <div class="service-item">
              <div class="img">
                <img src="assets/img/services-1.jpg" class="img-fluid" alt="Gestión de Usuarios">
              </div>
              <div class="details position-relative">
                <div class="icon">
                  <i class="bi bi-people-fill"></i>
                </div>
                <a href="#" class="stretched-link">
                  <h3>Gestión de Usuarios</h3>
                </a>
                <p>Administra estudiantes y docentes de manera eficiente. Importa datos masivamente, gestiona perfiles y mantén un control completo de la comunidad educativa.</p>
              </div>
            </div>
          </div><!-- End Service Item -->

          <div class="col-xl-4 col-md-6" data-aos="zoom-in" data-aos-delay="300">
            <div class="service-item">
              <div class="img">
                <img src="assets/img/services-2.jpg" class="img-fluid" alt="Red Social">
              </div>
              <div class="details position-relative">
                <div class="icon">
                  <i class="bi bi-share"></i>
                </div>
                <a href="#" class="stretched-link">
                  <h3>Red Social Integrada</h3>
                </a>
                <p>Publicaciones, comentarios y reacciones en tiempo real. Conecta a estudiantes y docentes en un entorno colaborativo y seguro.</p>
              </div>
            </div>
          </div><!-- End Service Item -->

          <div class="col-xl-4 col-md-6" data-aos="zoom-in" data-aos-delay="400">
            <div class="service-item">
              <div class="img">
                <img src="assets/img/services-3.jpg" class="img-fluid" alt="Calendario Académico">
              </div>
              <div class="details position-relative">
                <div class="icon">
                  <i class="bi bi-calendar-week"></i>
                </div>
                <a href="#" class="stretched-link">
                  <h3>Calendario Académico</h3>
                </a>
                <p>Gestiona eventos, exámenes y actividades académicas. Importa calendarios masivamente y mantén a toda la comunidad informada.</p>
              </div>
            </div>
          </div><!-- End Service Item -->

        </div>

      </div>

    </section><!-- /Features Section -->
    
    <!-- Tools Section -->
    <section id="tools" class="services-2 section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Herramientas del Sistema</h2>
        <p>FUNCIONALIDADES AVANZADAS PARA TU INSTITUCIÓN</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-4">

          <div class="col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="service-item d-flex position-relative h-100">
              <i class="bi bi-newspaper icon flex-shrink-0"></i>
              <div>
                <h4 class="title"><a href="#" class="stretched-link">Gestión de Noticias</a></h4>
                <p class="description">Publica y gestiona noticias institucionales con imágenes y programación automática. Mantén informada a toda la comunidad educativa.</p>
              </div>
            </div>
          </div><!-- End Service Item -->

          <div class="col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="service-item d-flex position-relative h-100">
              <i class="bi bi-award icon flex-shrink-0"></i>
              <div>
                <h4 class="title"><a href="#" class="stretched-link">Sistema de Becas</a></h4>
                <p class="description">Administra becas estudiantiles, seguimiento de solicitudes y gestión completa del proceso de apoyo económico.</p>
              </div>
            </div>
          </div><!-- End Service Item -->

          <div class="col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="service-item d-flex position-relative h-100">
              <i class="bi bi-diagram-3 icon flex-shrink-0"></i>
              <div>
                <h4 class="title"><a href="#" class="stretched-link">Gestión de Grupos</a></h4>
                <p class="description">Organiza estudiantes por carreras y semestres. Facilita la gestión académica y la comunicación por grupos.</p>
              </div>
            </div>
          </div><!-- End Service Item -->

          <div class="col-md-6" data-aos="fade-up" data-aos-delay="400">
            <div class="service-item d-flex position-relative h-100">
              <i class="bi bi-clock-history icon flex-shrink-0"></i>
              <div>
                <h4 class="title"><a href="#" class="stretched-link">Horarios Docentes</a></h4>
                <p class="description">Asigna y gestiona horarios de docentes por materia. Optimiza la distribución de carga académica.</p>
              </div>
            </div>
          </div><!-- End Service Item -->

          <div class="col-md-6" data-aos="fade-up" data-aos-delay="500">
            <div class="service-item d-flex position-relative h-100">
              <i class="bi bi-graph-up icon flex-shrink-0"></i>
              <div>
                <h4 class="title"><a href="#" class="stretched-link">Dashboard Analítico</a></h4>
                <p class="description">Monitorea estadísticas en tiempo real: usuarios activos, publicaciones, noticias y actividades recientes.</p>
              </div>
            </div>
          </div><!-- End Service Item -->

          <div class="col-md-6" data-aos="fade-up" data-aos-delay="600">
            <div class="service-item d-flex position-relative h-100">
              <i class="bi bi-shield-check icon flex-shrink-0"></i>
              <div>
                <h4 class="title"><a href="#" class="stretched-link">Seguridad Avanzada</a></h4>
                <p class="description">Sistema de autenticación robusto con roles diferenciados para estudiantes, docentes y administradores.</p>
              </div>
            </div>
          </div><!-- End Service Item -->

        </div>

      </div>

    </section><!-- /Tools Section -->

    <!-- Team Section -->
    <section id="team" class="team section light-background">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Desarrollado por Corvus Byte</h2>
        <p>ESPECIALISTAS EN SOLUCIONES TECNOLÓGICAS EDUCATIVAS</p>
      </div><!-- End Section Title -->

      <div class="container">

        <div class="row gy-5">

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
            <div class="member">
              <div class="pic"><img src="assets/img/team/team-1.jpg" class="img-fluid" alt="Desarrollo Web"></div>
              <div class="member-info">
                <h4>Desarrollo Web</h4>
                <span>Laravel & MongoDB</span>
                <div class="social">
                  <a href=""><i class="bi bi-code-slash"></i></a>
                  <a href=""><i class="bi bi-github"></i></a>
                  <a href=""><i class="bi bi-linkedin"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
            <div class="member">
              <div class="pic"><img src="assets/img/team/team-2.jpg" class="img-fluid" alt="UX/UI Design"></div>
              <div class="member-info">
                <h4>UX/UI Design</h4>
                <span>Experiencia de Usuario</span>
                <div class="social">
                  <a href=""><i class="bi bi-palette"></i></a>
                  <a href=""><i class="bi bi-behance"></i></a>
                  <a href=""><i class="bi bi-dribbble"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
            <div class="member">
              <div class="pic"><img src="assets/img/team/team-3.jpg" class="img-fluid" alt="Soporte Técnico"></div>
              <div class="member-info">
                <h4>Soporte Técnico</h4>
                <span>Implementación & Mantenimiento</span>
                <div class="social">
                  <a href=""><i class="bi bi-headset"></i></a>
                  <a href=""><i class="bi bi-wrench"></i></a>
                  <a href=""><i class="bi bi-gear"></i></a>
                </div>
              </div>
            </div>
          </div><!-- End Team Member -->

        </div>

      </div>

    </section><!-- /Team Section -->

    <!-- Contact Section -->
    <section id="contact" class="contact section">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Contacto</h2>
        <p>¿Interesado en implementar SchoolConnect en tu institución?</p>
      </div><!-- End Section Title -->

      <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-4">
          <div class="col-lg-6 ">
            <div class="row gy-4">

              <div class="col-lg-12">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="200">
                  <i class="bi bi-geo-alt"></i>
                  <h3>Corvus Byte</h3>
                  <p>Especialistas en Soluciones Tecnológicas Educativas</p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="300">
                  <i class="bi bi-telephone"></i>
                  <h3>Llámanos</h3>
                  <p>+52 55 1234 5678</p>
                </div>
              </div><!-- End Info Item -->

              <div class="col-md-6">
                <div class="info-item d-flex flex-column justify-content-center align-items-center" data-aos="fade-up" data-aos-delay="400">
                  <i class="bi bi-envelope"></i>
                  <h3>Escríbenos</h3>
                  <p>info@corvusbyte.com</p>
                </div>
              </div><!-- End Info Item -->

            </div>
          </div>

          <div class="col-lg-6">
            <form action="forms/contact.php" method="post" class="php-email-form" data-aos="fade-up" data-aos-delay="500">
              <div class="row gy-4">

                <div class="col-md-6">
                  <input type="text" name="name" class="form-control" placeholder="Tu Nombre" required="">
                </div>

                <div class="col-md-6 ">
                  <input type="email" class="form-control" name="email" placeholder="Tu Email" required="">
                </div>

                <div class="col-md-12">
                  <input type="text" class="form-control" name="subject" placeholder="Institución" required="">
                </div>

                <div class="col-md-12">
                  <textarea class="form-control" name="message" rows="4" placeholder="Cuéntanos sobre tu proyecto educativo" required=""></textarea>
                </div>

                <div class="col-md-12 text-center">
                  <div class="loading">Enviando...</div>
                  <div class="error-message"></div>
                  <div class="sent-message">¡Tu mensaje ha sido enviado! Te contactaremos pronto.</div>

                  <button type="submit">Solicitar Información</button>
                </div>

              </div>
            </form>
          </div><!-- End Contact Form -->

        </div>

      </div>

    </section><!-- /Contact Section -->

  </main>

  <footer id="footer" class="footer dark-background">

    <div class="container footer-top">
      <div class="row gy-4">
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="#" class="logo d-flex align-items-center">
            <span class="sitename">SchoolConnect</span>
          </a>
          <div class="footer-contact pt-3">
            <p>Desarrollado por Corvus Byte</p>
            <p>Soluciones Tecnológicas Educativas</p>
            <p class="mt-3"><strong>Teléfono:</strong> <span>+52 55 1234 5678</span></p>
            <p><strong>Email:</strong> <span>info@corvusbyte.com</span></p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href=""><i class="bi bi-twitter-x"></i></a>
            <a href=""><i class="bi bi-facebook"></i></a>
            <a href=""><i class="bi bi-instagram"></i></a>
            <a href=""><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Enlaces Útiles</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="#hero">Inicio</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#about">Acerca de</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#features">Características</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#tools">Herramientas</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#contact">Contacto</a></li>
          </ul>
        </div>

        <div class="col-lg-2 col-md-3 footer-links">
          <h4>Nuestros Servicios</h4>
          <ul>
            <li><i class="bi bi-chevron-right"></i> <a href="#features">Gestión Estudiantil</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#features">Red Social Académica</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#tools">Calendario Académico</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#tools">Sistema de Becas</a></li>
            <li><i class="bi bi-chevron-right"></i> <a href="#tools">Dashboard Analítico</a></li>
          </ul>
        </div>

        <div class="col-lg-4 col-md-12 footer-newsletter">
          <h4>Newsletter</h4>
          <p>Suscríbete para recibir las últimas noticias sobre SchoolConnect y actualizaciones del sistema</p>
          <form action="forms/newsletter.php" method="post" class="php-email-form">
            <div class="newsletter-form"><input type="email" name="email"><input type="submit" value="Suscribirse"></div>
            <div class="loading">Enviando...</div>
            <div class="error-message"></div>
            <div class="sent-message">¡Tu suscripción ha sido enviada! Gracias.</div>
          </form>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span>Copyright</span> <strong class="px-1 sitename">SchoolConnect</strong> <span>Desarrollado por Corvus Byte. Todos los derechos reservados</span></p>
      <div class="credits">
        Solución tecnológica educativa diseñada para revolucionar la gestión estudiantil
      </div>
    </div>

  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="assets/vendor/php-email-form/validate.js"></script>
  <script src="assets/vendor/aos/aos.js"></script>
  <script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>

  <!-- Main JS File -->
  <script src="assets/js/main.js"></script>

</body>

</html>