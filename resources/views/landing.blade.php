<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muslim Level Up Academy - Ramadhan Berjaya</title>
    <link href="{{ asset('css/landing.css') }}" rel="stylesheet">
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar">
        <div class="container nav-container">
            <!-- Left: Instagram -->
            <div class="nav-left">
                <a href="https://instagram.com/muslimlup.ac.id" class="nav-social-item">
                    <!-- Instagram SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                    </svg>
                    @muslimlup.ac.id
                </a>
            </div>

            <!-- Center: Logo -->
            <div class="nav-center">
                <a href="/">
                    <img src="{{ asset('images/asset/Asset-9@4x.png') }}" alt="Muslim Level Up Academy"
                        class="nav-logo">
                </a>
            </div>

            <!-- Right: Whatsapp, Login -->
            <div class="nav-right">
                <a href="https://wa.me/6285782876666" class="nav-social-item">
                    <!-- WhatsApp SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="currentColor" class="bi bi-whatsapp" style="color: #25D366;">
                        <path
                            d="M13.601 2.326A7.854 7.854 0 0 0 7.994 0C3.627 0 .068 3.558.064 7.926c0 1.399.366 2.76 1.057 3.965L0 16l4.204-1.102a7.933 7.933 0 0 0 3.79.965h.004c4.368 0 7.926-3.558 7.93-7.93A7.898 7.898 0 0 0 13.6 2.326zM7.994 14.521a6.573 6.573 0 0 1-3.356-.92l-.24-.144-2.494.654.666-2.433-.156-.251a6.56 6.56 0 0 1-1.007-3.505c0-3.626 2.957-6.584 6.591-6.584a6.56 6.56 0 0 1 4.66 1.931 6.557 6.557 0 0 1 1.928 4.66c-.004 3.639-2.961 6.592-6.592 6.592zm3.615-4.934c-.197-.099-1.17-.578-1.353-.646-.182-.065-.315-.099-.445.099-.133.197-.513.646-.627.775-.114.133-.232.148-.43.05-.197-.1-.836-.308-1.592-.985-.59-.525-.985-1.175-1.103-1.372-.114-.198-.011-.304.088-.403.087-.088.197-.232.296-.346.1-.114.133-.198.198-.33.065-.134.034-.248-.015-.347-.05-.099-.445-1.076-.612-1.47-.16-.389-.323-.335-.445-.34-.114-.007-.247-.007-.38-.007a.729.729 0 0 0-.529.247c-.182.198-.691.677-.691 1.654 0 .977.71 1.916.81 2.049.098.133 1.394 2.132 3.383 2.992.47.205.84.326 1.129.418.475.152.904.129 1.246.08.38-.058 1.171-.48 1.338-.943.164-.464.164-.86.114-.943-.049-.084-.182-.133-.38-.232z" />
                    </svg>
                    085782876666
                </a>
                <a href="{{ route('login') }}" class="btn-login">Login</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero container">
        <div class="hero-content">
            <h3 style="color: #333; font-weight: 600; font-size: 1.2rem; margin-bottom: 0.5rem;">MLUP Berjaya</h3>
            <h1 class="hero-title" style="color: #4c6ef5;">Berkah Ramadhan<br>Menuju Raya</h1>
            <p class="hero-subtitle">
                Gerakan Kolaborasi Pendidikan dan Kebutuhan
                Dasar Mahasiswa Indonesia. Salurkan Zakat,
                Infaq, dan Shadaqah terbaikmu untuk membantu
                pejuang ilmu tetap tegak di bangku kuliah
            </p>
            <a href="#donate" class="btn-donate">Donasi Sekarang</a>
        </div>
        <div class="hero-image">
            <img src="{{ asset('images/asset/Asset-1@4x.png') }}" alt="Mosque & Ketupat">
        </div>
    </section>

    <!-- Collaboration Section -->
    <section class="collaboration">
        <div class="container">
            <p class="collab-text">In collaboration with:</p>
            <div class="collab-logos">
                <img src="{{ asset('images/asset/Asset-2@4x.png') }}" class="collab-logo-full" alt="Collaborators"
                    style="width: 100%; max-width: 900px; height: auto;">
            </div>
        </div>
    </section>

    <!-- Problem Section -->
    <section class="problem-section container">
        <div class="problem-image">
            <img src="{{ asset('images/asset/Asset-3@4x.png') }}" alt="Graduation Cap"
                style="width: 100%; max-width: 400px;">
        </div>
        <div class="problem-content">
            <h2 class="problem-title">Jangan biarkan<br>mimpi mereka terhenti.</h2>
            <p class="problem-text">
                Di balik semangat menuntut ilmu, banyak
                mahasiswa pejuang pendidikan yang terancam
                putus sekolah karena himpitan ekonomi. Biaya
                hidup dan kebutuhan dasar seringkali menjadi
                tembok besar bagi masa depan mereka.
            </p>
            <div class="quote-box">
                "Perumpamaan orang yang menginfakkan
                hartanya di jalan Allah seperti sebutir biji yang
                menumbuhkan tujuh tangkai..."
                <br><strong>— Q.S. Al-Baqarah: 261</strong>
            </div>
        </div>
    </section>

    <!-- Solution Section -->
    <section class="solution-section" id="donate">
        <div class="container flex justify-between items-center" style="width: 100%;">
            <div class="solution-left">
                <h3 style="font-size: 1.2rem; opacity: 0.9;">Solusi nyata untuk</h3>
                <h2 style="font-size: 2.5rem; font-weight: 700; line-height: 1.2;">Mahasiswa<br>Muslim Indonesia.</h2>
                <p style="margin-top: 1rem; opacity: 0.8; font-size: 0.9rem;">
                    MLUP Berjaya hadir untuk memastikan dana
                    ZIS Anda tersalurkan kepada asnaf yang tepat:
                    Mahasiswa muslim yang sedang berjuang di
                    jalan ilmu (Fi Sabilillah) dan mereka yang
                    kekurangan (Fakir/Miskin).
                </p>
            </div>
            <div class="solution-right">
                <p>Salurkan melalui:</p>
                <div style="margin: 1rem 0;">
                    <img src="{{ asset('images/asset/Asset-4@4x.png') }}" alt="blu BCA Digital" style="height: 140px;">
                </div>
                <div class="bank-number">090109627811</div>
                <p>a.n. <strong>Ahmad Bustan Djatmadipura</strong></p>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features-section container">
        <h2 class="features-title" style="color: #4c6ef5;">Kontribusi Nyata, <span>Reward Seketika</span></h2>
        <div class="features-grid">
            <!-- Card 1 -->
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <img src="{{ asset('images/asset/Asset-5@4x.png') }}" alt="Voucher">
                </div>
                <h3>Akses Master Voucher</h3>
                <p>Dapatkan diskon
                    khusus dari berbagai
                    merchant hanya
                    dengan minimal
                    penyaluran Rp 35.000</p>
            </div>
            <!-- Card 2 -->
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <img src="{{ asset('images/asset/Asset-6@4x.png') }}" alt="Mosque">
                </div>
                <h3>Keberkahan Ibadah
                    Menunaikan
                    kewajiban</h3>
                <p>Zakat dan
                    Infaq di bulan suci.</p>
            </div>
            <!-- Card 3 -->
            <div class="feature-card">
                <div class="feature-icon-wrapper">
                    <img src="{{ asset('images/asset/Asset-7@4x.png') }}" alt="Globe">
                </div>
                <h3>Ekosistem Kebaikan</h3>
                <p>Menjadi bagian dari
                    jaringan Muslim Level
                    Up Academy</p>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works">
        <div class="container how-grid">
            <div class="how-steps">
                <h2 style="margin-bottom: 2rem; font-size: 2rem;">How It Works</h2>
                <div class="steps">
                    <div class="step-item">
                        <div class="step-number">Step 1</div>
                        <div class="step-text">
                            <h4>Donasi / Transfer ke Rekening</h4>
                            <p>BCA Digital 090109627811 (a.n. Ahmad Bustan Djatmadipura).</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">Step 2</div>
                        <div class="step-text">
                            <h4>Konfirmasi Pembayaran</h4>
                            <p>Konfirmasi pembayaran untuk mendapatkan akses voucher.</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-number">Step 3</div>
                        <div class="step-text">
                            <h4>Scan QR & Redeem Promo</h4>
                            <p>Nikmati promo spesial
                                hingga 50% di merchant partner kami.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="how-image">
                <img src="{{ asset('images/asset/Asset-8@4x.png') }}" alt="Donation Box" style="width: 100%;">
            </div>
        </div>
    </section>

    <!-- Footer CTA -->
    <section class="footer-cta container">
        <div class="footer-cta-left">
            <p style="color: #4c6ef5; font-weight: 600; margin-bottom: 0.5rem;">Siap berbagi berkah hari ini?</p>
            <h2 class="footer-cta-title">Ubah masa depan banyak mahasiswa melalui Zakat dan Infaq Anda.</h2>
        </div>
        <div class="footer-bank-details">
            <div style="text-align: right; color: #4c6ef5;">
                <img src="{{ asset('images/asset/Asset-4@4x.png') }}" alt="blu BCA Digital" style="height: 80px;">
            </div>
            <div class="bank-number" style="color: #4c6ef5;">090109627811</div>
            <p style="color: #4c6ef5;">a.n. <strong>Ahmad Bustan Djatmadipura</strong></p>
        </div>
    </section>

    <!-- Footer Bottom -->
    <footer class="footer-bottom">
        <div class="container footer-content">
            <!-- Left: Socials -->
            <div class="footer-left">
                <a href="https://instagram.com/muslimlup.ac.id" class="footer-social-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                        <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                    </svg>
                    @muslimlup.ac.id
                </a>
                <a href="https://wa.me/6285782876666" class="footer-social-item">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path
                            d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                        </path>
                    </svg>
                    085782876666
                </a>
            </div>

            <!-- Center: Logo -->
            <div class="footer-center">
                <img src="{{ asset('images/asset/Asset-9@4x.png') }}" alt="Muslim Level Up Academy"
                    style="height: 50px; filter: brightness(0) invert(1);">
            </div>

            <!-- Right: Tagline -->
            <div class="footer-right">
                <p>Program penyaluran ZIS yang<br>transparan, berdampak, dan apresiatif</p>
            </div>
        </div>
    </footer>

</body>

</html>