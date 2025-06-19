<!DOCTYPE html>
<html>

<head>
    <!-- la cabecera de todas las paginas -->
    <?= view('layout/base_head') ?>
    
    <!-- Animate.css -->
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />
    
    <!-- Custom CSS para mejoras adicionales -->
    <style>
        .team-card {
            background: linear-gradient(135deg,rgb(10, 25, 104) 0%,rgb(88, 5, 84) 100%);
            border: none !important;
            box-shadow: 0 1px 5px #00000099;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border-radius: 10px;
        }
        
        .team-card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        }
        
        .team-card .glow {
            display: none;
        }
        
        .team-card .card-content {
            background: rgba(255,255,255,0.95);
            backdrop-filter: blur(10px);
            margin: 15px;
            border-radius: 15px;
            padding: 20px;
            position: relative;
            z-index: 2;
        }
        
        .profile-img {
            border: 4px solid #fff;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.31);
            transition: all 0.3s ease;
            position: relative;
            z-index: 3;
        }
        
        .profile-img:hover {
            transform: scale(1.1) rotate(5deg);
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
        }
        
        .store-image {
            border-radius: 20px;
            transition: all 0.4s ease;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            cursor: pointer;
            position: relative;
            overflow: hidden;
        }
        
        .store-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(235, 14, 243, 0.8), rgba(116, 4, 228, 0.8));
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .store-image:hover::after {
            opacity: 1;
        }
        
        .store-image:hover {
            transform: scale(1.05);
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }
        
        .store-container {
            position: relative;
            overflow: hidden;
            border-radius: 20px;
        }
        
        .store-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(45deg, rgba(102, 126, 234, 0.9), rgba(118, 75, 162, 0.9));
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: all 0.3s ease;
            z-index: 2;
        }
        
        .store-container:hover .store-overlay {
            opacity: 1;
        }
        
        .store-info {
            text-align: center;
            color: white;
            transform: translateY(20px);
            transition: transform 0.3s ease;
        }
        
        .store-container:hover .store-info {
            transform: translateY(0);
        }
        
        .section-title {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: bold;
            position: relative;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 2px;
        }
        
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .floating-element {
            position: absolute;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-element:nth-child(1) {
            width: 80px;
            height: 80px;
            top: 10%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .floating-element:nth-child(2) {
            width: 60px;
            height: 60px;
            top: 70%;
            right: 10%;
            animation-delay: 2s;
        }
        
        .floating-element:nth-child(3) {
            width: 40px;
            height: 40px;
            top: 40%;
            left: 80%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(180deg); }
        }
        
        .hover-glow {
            transition: all 0.3s ease;
        }
        
        .hover-glow:hover {
            box-shadow: 0 0 30px rgba(102, 126, 234, 0.6);
        }
        
        /* Perspective container - no longer needed */
        
        /* Responsive improvements */
        @media (max-width: 768px) {
            .team-card {
                margin-bottom: 2rem;
            }
            
            .store-container {
                margin-bottom: 2rem;
            }
        }
        
        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }
        
        /* Loading animation for images */
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?= view('layout/navbar') ?>

    <!-- Contenido específico de la página -->
    <main class="container-fluid position-relative">
        <!-- Floating background elements -->
        <div class="floating-elements">
            <div class="floating-element"></div>
            <div class="floating-element"></div>
            <div class="floating-element"></div>
        </div>
        
        <!-- Team Section -->
        <section>
            <h1 class="section-title text-center my-4 animate__animated animate__fadeInDown">
                Nuestro Equipo
            </h1>

            <div class="container marketing">
                <div class="row align-items-center justify-content-around">
                    <!-- Team Member 1 -->
                    <div class="col-11 col-md-5 col-lg-4 my-1 animate__animated animate__fadeInLeft animate__delay-1s">
                        <div class="team-card rounded-3 h-100">
                            <div class="card-content">
                                <div class="d-flex align-items-center justify-content-center mb-4">
                                    <img src="https://i.pinimg.com/280x280_RS/18/d6/0a/18d60afe334e2434eaae0d6ad04b0649.jpg" 
                                         alt="Ariel Antinori" 
                                         class="profile-img rounded-circle hover-glow" 
                                         width="140" 
                                         height="140"
                                         loading="lazy">
                                </div>

                                <h2 class="text-center fw-bold mb-3 animate__animated animate__fadeInUp animate__delay-2s">
                                    Ariel Antinori
                                </h2>
                                <div class="text-center mb-2">
                                    <span class="badge bg-primary rounded-pill px-3 py-2">
                                        <i class="fas fa-code me-1"></i>
                                        Desarrollador Senior
                                    </span>
                                </div>
                                <p class="text-muted text-center animate__animated animate__fadeInUp animate__delay-2s" 
                                   style="text-align: justify;">
                                    Posee una amplia experiencia en el desarrollo de software, con habilidades sólidas en 
                                    varios lenguajes de programación y tecnologías relevantes para el dominio de los juegos 
                                    y el comercio electrónico.
                                </p>
                                
                                <!-- Social Links -->
                                <div class="text-center mt-3">
                                    <a href="#" class="btn btn-outline-primary btn-sm rounded-pill me-2 animate__animated animate__bounceIn animate__delay-3s">
                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-primary btn-sm rounded-pill me-2 animate__animated animate__bounceIn animate__delay-3s">
                                        <i class="fab fa-github"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-primary btn-sm rounded-pill animate__animated animate__bounceIn animate__delay-3s">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Team Member 2 -->
                    <div class="col-11 col-md-5 col-lg-4 my-1 animate__animated animate__fadeInRight animate__delay-1s">
                        <div class="team-card rounded-3 h-100">
                            <div class="card-content">
                                <div class="d-flex align-items-center justify-content-center mb-4">
                                    <img src="<?= base_url('public/assets/img/yo.webp') ?>" 
                                         alt="Andres Sena" 
                                         class="profile-img rounded-circle hover-glow" 
                                         width="140" 
                                         height="140"
                                         loading="lazy">
                                </div>

                                <h2 class="text-center fw-bold mb-3 animate__animated animate__fadeInUp animate__delay-2s">
                                    Andres Sena
                                </h2>
                                <div class="text-center mb-2">
                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                        <i class="fas fa-users me-1"></i>
                                        Líder Técnico
                                    </span>
                                </div>
                                <p class="text-muted text-center animate__animated animate__fadeInUp animate__delay-2s" 
                                   style="text-align: justify;">
                                    Ejerce un liderazgo técnico efectivo, guiando al equipo de desarrollo en la implementación 
                                    de soluciones técnicas innovadoras y escalables que satisfagan las necesidades del negocio 
                                    y los clientes de la tienda de juegos.
                                </p>
                                
                                <!-- Social Links -->
                                <div class="text-center mt-3">
                                    <a href="#" class="btn btn-outline-success btn-sm rounded-pill me-2 animate__animated animate__bounceIn animate__delay-3s">
                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-success btn-sm rounded-pill me-2 animate__animated animate__bounceIn animate__delay-3s">
                                        <i class="fab fa-github"></i>
                                    </a>
                                    <a href="#" class="btn btn-outline-success btn-sm rounded-pill animate__animated animate__bounceIn animate__delay-3s">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Stores Section -->
        <section class="py-5">
            <h1 class="section-title text-center mb-5 animate__animated animate__fadeInUp animate__delay-4s">
                Nuestros Establecimientos
            </h1>
            
            <div class="row align-items-stretch mx-1 mx-md-5">
                <!-- Store 1 -->
                <div class="col-md-6 my-3 animate__animated animate__fadeInLeft animate__delay-5s">
                    <div class="store-container h-100">
                        <img src="<?= base_url('public/assets/img/tienda.jpg') ?>" 
                             class="store-image w-100 h-100" 
                             style="object-fit: cover; min-height: 300px;"
                             alt="Tienda Principal"
                             loading="lazy">
                        <div class="store-overlay">
                            <div class="store-info animate__animated animate__fadeInUp">
                                <h3 class="fw-bold mb-2">
                                    <i class="fas fa-store me-2"></i>
                                    Tienda Principal
                                </h3>
                                <p class="mb-3">Centro comercial premium</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Store 2 -->
                <div class="col-md-6 my-3 animate__animated animate__fadeInRight animate__delay-5s">
                    <div class="store-container h-100">
                        <img src="<?= base_url('public/assets/img/tienda-gamer.webp') ?>" 
                             class="store-image w-100 h-100" 
                             style="object-fit: cover; min-height: 300px;"
                             alt="Tienda Gamer"
                             loading="lazy">
                        <div class="store-overlay">
                            <div class="store-info animate__animated animate__fadeInUp">
                                <h3 class="fw-bold mb-2">
                                    <i class="fas fa-gamepad me-2"></i>
                                    Tienda Gamer
                                </h3>
                                <p class="mb-3">Especializada en gaming</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Store Info Cards -->
            <div class="row mx-1 mx-md-5 mt-4 animate__animated animate__fadeInUp animate__delay-6s">
                <div class="col-md-4 my-2">
                    <div class="card border-0 shadow-sm h-100 hover-glow">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-clock fa-3x text-primary"></i>
                            </div>
                            <h5 class="card-title">Horarios Extendidos</h5>
                            <p class="card-text text-muted">Lunes a Domingo de 9:00 AM a 10:00 PM</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 my-2">
                    <div class="card border-0 shadow-sm h-100 hover-glow">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-shipping-fast fa-3x text-success"></i>
                            </div>
                            <h5 class="card-title">Entrega Rápida</h5>
                            <p class="card-text text-muted">Delivery en menos de 24 horas en toda la ciudad</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4 my-2">
                    <div class="card border-0 shadow-sm h-100 hover-glow">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="fas fa-headset fa-3x text-info"></i>
                            </div>
                            <h5 class="card-title">Soporte 24/7</h5>
                            <p class="card-text text-muted">Atención al cliente disponible las 24 horas</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <?= view('layout/footer') ?>

    <!-- Scripts generales que se usan en todas las paginas -->
    <?= view('layout/base_scripts') ?>
    
    <!-- Script para animaciones adicionales -->
    <script>
        // Intersection Observer para animaciones al hacer scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                }
            });
        }, observerOptions);

        // Observar elementos que no tienen animación inicial
        document.querySelectorAll('.card:not([class*="animate__"])').forEach(card => {
            observer.observe(card);
        });

        // Efecto parallax suave en elementos flotantes
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelectorAll('.floating-element');
            const speed = 0.5;

            parallax.forEach(element => {
                const yPos = -(scrolled * speed);
                element.style.transform = `translateY(${yPos}px)`;
            });
        });

        // Preload de imágenes con efecto shimmer
        document.querySelectorAll('img').forEach(img => {
            img.addEventListener('load', function() {
                this.classList.remove('loading-shimmer');
            });
            
            img.addEventListener('error', function() {
                this.classList.remove('loading-shimmer');
                this.style.backgroundColor = '#f8f9fa';
            });
            
            // Añadir efecto shimmer inicial si la imagen no está cargada
            if (!img.complete) {
                img.classList.add('loading-shimmer');
            }
        });

        // Smooth scroll para botones de navegación
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Efecto hover simple para las tarjetas del equipo
        const teamCards = document.querySelectorAll('.team-card');
        
        teamCards.forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'scale(1.05)';
            });
            
            card.addEventListener('mouseleave', () => {
                card.style.transform = 'scale(1)';
            });
        });
    </script>
</body>

</html>