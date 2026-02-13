<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ramadhan Berkah - Program Voucher Sedekah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&family=Amiri:wght@400;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary-teal: #0d7377;
            --primary-gold: #d4af37;
            --dark-teal: #14213d;
            --light-cream: #fef9f3;
            --soft-orange: #ff8c42;
            --purple-dusk: #6a4c93;
        }

        body {
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
            background: var(--light-cream);
        }

        /* Navbar */
        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 0.5rem 5%;
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15);
        }

        .logo {
            font-family: 'Amiri', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary-teal);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo::before {
            content: '☪';
            color: var(--primary-gold);
            font-size: 2rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark-teal);
            font-weight: 500;
            transition: color 0.3s;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--primary-gold);
            transition: width 0.3s;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-cta {
            background: linear-gradient(135deg, var(--primary-teal), var(--primary-gold));
            color: white;
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .nav-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(13, 115, 119, 0.3);
        }

        /* Hero Section */
        .hero {
            position: relative;
            height: 100vh;
            width: 100%;
            overflow: hidden;
            display: flex;
            align-items: flex-end; /* Align content to bottom */
            justify-content: flex-start; /* Align content to left */
            color: white;
            padding: 0;
        }

        .hero-bg-media {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -1;
            /* Placeholder background color while loading */
            background-color: var(--dark-teal);
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, rgba(0,0,0,0) 50%);
            z-index: 0;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            padding: 3rem 5%;
            max-width: 800px;
            text-align: left;
            margin-bottom: 2rem;
            animation: fadeInUp 1s ease-out;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero h1 {
            font-family: 'Amiri', serif;
            font-size: 3.5rem;
            margin-bottom: 0.5rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
            line-height: 1.1;
        }

        .hero .subtitle {
            font-size: 1.1rem;
            margin-bottom: 1rem;
            opacity: 0.9;
            font-weight: 300;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.5);
        }

        .hero .verse {
            font-family: 'Amiri', serif;
            font-size: 1rem;
            font-style: italic;
            opacity: 0.8;
            border-left: 3px solid var(--primary-gold);
            padding-left: 1rem;
            margin-top: 1.5rem;
        }

        .cta-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 1rem 2.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: white;
            color: var(--primary-teal);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.3);
        }

        .btn-secondary {
            background: transparent;
            color: white;
            border: 2px solid white;
        }

        .btn-secondary:hover {
            background: white;
            color: var(--primary-teal);
        }

        /* Features Section */
        .features {
            padding: 6rem 5%;
            background: white;
        }

        .section-title {
            text-align: center;
            margin-bottom: 4rem;
        }

        .section-title h2 {
            font-family: 'Amiri', serif;
            font-size: 3rem;
            color: var(--dark-teal);
            margin-bottom: 1rem;
        }

        .section-title p {
            color: #666;
            font-size: 1.1rem;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 3rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            padding: 2.5rem;
            border-radius: 20px;
            text-align: center;
            transition: all 0.3s;
            border: 1px solid rgba(13, 115, 119, 0.1);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary-teal), var(--primary-gold));
            transform: scaleX(0);
            transition: transform 0.3s;
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(13, 115, 119, 0.15);
        }

        .feature-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            display: inline-block;
            animation: bounce 2s ease-in-out infinite;
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        .feature-card h3 {
            color: var(--primary-teal);
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        /* About Section */
        .about {
            padding: 6rem 5%;
            background: linear-gradient(135deg, var(--light-cream), #fff5e6);
        }

        .about-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .about-image {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        .about-image img {
            width: 100%;
            height: auto;
            display: block;
            transition: transform 0.5s;
        }

        .about-image:hover img {
            transform: scale(1.05);
        }

        .about-text h2 {
            font-family: 'Amiri', serif;
            font-size: 2.5rem;
            color: var(--dark-teal);
            margin-bottom: 1.5rem;
        }

        .about-text p {
            color: #555;
            line-height: 1.8;
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 3rem;
        }

        .stat-item {
            text-align: center;
            padding: 1.5rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-teal);
            margin-bottom: 0.5rem;
        }

        .stat-label {
            color: #666;
            font-size: 0.9rem;
        }

        /* CTA Section */
        .cta-section {
            padding: 6rem 5%;
            background: linear-gradient(135deg, var(--primary-teal), var(--purple-dusk)),
                        url('/images/landing/mosque.png') center/cover;
            background-blend-mode: overlay;
            text-align: center;
            color: white;
            position: relative;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(13, 115, 119, 0.85);
        }

        .cta-content {
            max-width: 800px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .cta-content h2 {
            font-family: 'Amiri', serif;
            font-size: 3rem;
            margin-bottom: 1.5rem;
        }

        .cta-content p {
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            opacity: 0.95;
        }

        /* Footer */
        .footer {
            background: var(--dark-teal);
            color: white;
            padding: 3rem 5%;
            text-align: center;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
        }

        .footer-links a {
            color: white;
            text-decoration: none;
            opacity: 0.8;
            transition: opacity 0.3s;
        }

        .footer-links a:hover {
            opacity: 1;
        }

        .copyright {
            opacity: 0.7;
            font-size: 0.9rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .hero .subtitle {
                font-size: 1.1rem;
            }

            .nav-links {
                display: none;
            }

            .about-content {
                grid-template-columns: 1fr;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .section-title h2 {
                font-size: 2rem;
            }

            .cta-content h2 {
                font-size: 2rem;
            }
        }

        /* Scroll animations */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s, transform 0.6s;
        }

        .fade-in.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="logo">Ramadhan Berkah</div>
        <ul class="nav-links">
            <li><a href="#beranda">Beranda</a></li>
            <li><a href="#tentang">Tentang</a></li>
            <li><a href="#fitur">Fitur</a></li>
        </ul>
        <a href="{{ route('login') }}" class="nav-cta">Masuk</a>
    </nav>

    <!-- Hero Section -->
    <!-- Hero Section -->
    <section class="hero" id="beranda">
        <!-- Background Media (Mosque Image) -->
        <img src="https://images.unsplash.com/photo-1542831371-29b0f74f9713?q=80&w=2070&auto=format&fit=crop" alt="Mosque Background" class="hero-bg-media">
        
        <!-- Overlay for better text readability -->
        <div class="hero-overlay"></div>

        <div class="hero-content">
            <h1>Berbagi Berkah<br>di Bulan Ramadhan</h1>
            <p class="subtitle">Wujudkan sedekah Anda melalui program voucher digital</p>
            <div class="verse">
                "Perumpamaan orang yang menginfakkan hartanya di jalan Allah seperti sebutir biji yang menumbuhkan tujuh tangkai..."
                <br><small>- QS. Al-Baqarah: 261</small>
            </div>
            <!-- CTA Buttons Removed as requested -->
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="fitur">
        <div class="section-title fade-in">
            <h2>Mengapa Ramadhan Berkah?</h2>
            <p>Platform sedekah digital yang mudah, aman, dan terpercaya</p>
        </div>
        <div class="features-grid">
            <div class="feature-card fade-in">
                <div class="feature-icon">🎁</div>
                <h3>Voucher Digital</h3>
                <p>Sistem voucher digital yang memudahkan distribusi sedekah kepada yang berhak dengan lebih efisien dan terukur.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon">🤝</div>
                <h3>Merchant Partner</h3>
                <p>Bekerja sama dengan merchant terpercaya untuk memastikan voucher dapat digunakan untuk kebutuhan pokok.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon">📊</div>
                <h3>Transparan & Akuntabel</h3>
                <p>Pantau penyaluran sedekah Anda secara real-time dengan sistem pelaporan yang transparan dan akuntabel.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon">⚡</div>
                <h3>Proses Cepat</h3>
                <p>Distribusi voucher yang cepat dan mudah, memastikan bantuan sampai tepat waktu kepada yang membutuhkan.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon">🔒</div>
                <h3>Aman & Terpercaya</h3>
                <p>Sistem keamanan berlapis untuk melindungi data dan memastikan voucher sampai ke tangan yang tepat.</p>
            </div>
            <div class="feature-card fade-in">
                <div class="feature-icon">💝</div>
                <div class="feature-icon">💝</div>
                <h3>Pahala Berlipat</h3>
                <p>Raih pahala berlipat ganda di bulan Ramadhan dengan kemudahan berbagi yang kami sediakan.</p>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="tentang">
        <div class="about-content">
            <div class="about-image fade-in">
                <img src="/images/landing/charity.png" alt="Charity Illustration">
            </div>
            <div class="about-text fade-in">
                <h2>Tentang Program Kami</h2>
                <p>
                    Ramadhan Berkah adalah platform digital yang memfasilitasi penyaluran sedekah melalui sistem voucher elektronik. 
                    Kami percaya bahwa teknologi dapat mempermudah berbagi kebaikan dan menjangkau lebih banyak orang yang membutuhkan.
                </p>
                <p>
                    Dengan sistem yang transparan dan terukur, setiap sedekah yang Anda berikan dapat dipantau penyalurannya, 
                    memastikan bantuan sampai kepada yang berhak dengan tepat sasaran.
                </p>
                <div class="stats">
                    <div class="stat-item">
                        <div class="stat-number">1000+</div>
                        <div class="stat-label">Voucher Tersalurkan</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Merchant Partner</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Keluarga Terbantu</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="cta-content">
            <h2>Mari Berbagi Kebaikan</h2>
            <p>Bergabunglah dengan ribuan orang yang telah menyalurkan sedekahnya melalui platform kami. Mulai berbagi berkah hari ini!</p>
            <a href="{{ route('login') }}" class="btn btn-primary">Mulai Sekarang →</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="logo" style="justify-content: center; margin-bottom: 2rem;">Ramadhan Berkah</div>
            <div class="footer-links">
                <a href="#beranda">Beranda</a>
                <a href="#tentang">Tentang</a>
                <a href="#fitur">Fitur</a>
                <a href="{{ route('login') }}">Masuk</a>
            </div>
            <div class="copyright">
                &copy; {{ date('Y') }} Ramadhan Berkah. Semua hak dilindungi.
            </div>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Fade in animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });

        // Smooth scroll
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
    </script>
</body>
</html>
