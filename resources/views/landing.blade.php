<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muslim Level Up Academy - Ramadhan Berjaya</title>
    <link href="{{ asset('css/landing.css') }}?v={{ time() }}" rel="stylesheet">
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
    <section class="hero container" style="position: relative;">
        <!-- Decorative Asset -->
        <div class="hero-decoration">
            <img src="{{ asset('images/asset/Asset-3@4x.png') }}" alt="Decoration">
        </div>

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
            <div class="video-stack-container">
                <!-- Video 1 (Initially Front) -->
                <div class="video-card front" id="video1">
                    <div class="video-number">01</div>
                    <video class="hero-video" playsinline muted>
                        <source src="{{ asset('vidio/1.mp4') }}" type="video/mp4">
                    </video>
                </div>
                <!-- Video 2 (Initially Back) -->
                <div class="video-card back" id="video2">
                    <div class="video-number">02</div>
                    <!-- Lazy load: use data-src on video tag directly -->
                    <video class="hero-video" playsinline muted data-src="{{ asset('vidio/2.mp4') }}">
                    </video>
                </div>

                <!-- Video Controls -->
                <div class="video-controls">
                    <button class="video-control-btn" id="togglePlayBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor" class="icon-pause hidden">
                            <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor" class="icon-play">
                            <path d="M8 5v14l11-7z" />
                        </svg>
                    </button>
                    <button class="video-control-btn" id="nextVideoBtn">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="currentColor">
                            <path d="M6 18l8.5-6L6 6v12zM16 6v12h2V6h-2z" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Post-sections code -->
    <!-- ... -->

    <!-- ... -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const v1 = document.getElementById('video1');
            const v2 = document.getElementById('video2');
            const toggleBtn = document.getElementById('togglePlayBtn');
            const nextBtn = document.getElementById('nextVideoBtn');
            const iconPause = toggleBtn.querySelector('.icon-pause');
            const iconPlay = toggleBtn.querySelector('.icon-play');

            // Initial State
            let currentFront = v1;
            let currentBack = v2;
            let isPlaying = false;
            let video2Loaded = false;

            function updatePlayIcon(playing) {
                if (playing) {
                    iconPause.classList.remove('hidden');
                    iconPlay.classList.add('hidden');
                } else {
                    iconPause.classList.add('hidden');
                    iconPlay.classList.remove('hidden');
                }
            }

            function loadVideo2() {
                if (!video2Loaded) {
                    const vid2 = v2.querySelector('video');
                    // Check for data-src on the video element itself
                    if (vid2.dataset.src) {
                        vid2.src = vid2.dataset.src;
                        vid2.load();
                        video2Loaded = true;
                        console.log("Video 2 loaded via Play trigger");
                    }
                }
            }

            // Toggle Play/Pause
            toggleBtn.addEventListener('click', function () {
                const video = currentFront.querySelector('video');

                // Trigger lazy load of video 2 when Play is clicked
                loadVideo2();

                if (video.paused) {
                    video.muted = false;
                    video.play();
                    isPlaying = true;
                } else {
                    video.pause();
                    isPlaying = false;
                }
                updatePlayIcon(isPlaying);
            });

            // Next Video
            nextBtn.addEventListener('click', function () {
                // Ensure video 2 is loaded if next is clicked before play
                loadVideo2();
                swapVideos();
            });

            function swapVideos() {
                const activeVideo = currentFront.querySelector('video');
                activeVideo.pause();

                // Animate current front to left and back
                currentFront.classList.remove('front');
                currentFront.style.transform = "translateX(-120%) scale(0.8) rotateY(10deg)";
                currentFront.style.opacity = "0";

                // Bring back video to front
                currentBack.classList.remove('back');
                currentBack.classList.add('front');

                const newFrontVideo = currentBack.querySelector('video');
                newFrontVideo.currentTime = 0;
                newFrontVideo.muted = false;
                newFrontVideo.play();

                isPlaying = true;
                updatePlayIcon(true);

                // After animation, move the old front to the back position
                setTimeout(() => {
                    currentFront.style.transition = 'none';
                    currentFront.classList.add('back');
                    currentFront.style.transform = '';
                    currentFront.style.opacity = '';

                    setTimeout(() => {
                        currentFront.style.transition = '';
                    }, 50);

                    // Swap references
                    let temp = currentFront;
                    currentFront = currentBack;
                    currentBack = temp;

                    // Mute the back video
                    currentBack.querySelector('video').muted = true;

                    // Setup next listener
                    const newActive = currentFront.querySelector('video');
                    newActive.onended = swapVideos;
                }, 600);
            }

            // Initialize listener on first video
            v1.querySelector('video').onended = swapVideos;

            // NOTE: We do NOT autoplay here. Waiting for user interaction as requested.
            // If autoplay is desired, we can uncomment the play below, but browsers might block unmuted autoplay.
            // Checks for autoplay could go here if needed.
        });
    </script>
    </div>
    </section>

    <!-- Post-sections code -->
    <!-- ... -->

    <!-- Post-sections code -->
    <!-- ... -->

    <section class="collaboration">
        <div class="container">
            <p class="collab-text">In collaboration with:</p>
            <div class="collab-logos">
                <img src="{{ asset('images/asset/Asset-2@4x.png') }}" class="collab-logo-full" alt="Collaborators"
                    style="width: 100%; max-width: 900px; height: auto;">
            </div>

            <!-- Community Support Section -->
            <div class="community-support" style="margin-top: 4rem;">
                <p class="collab-text" style="margin-bottom: 2rem; font-size: 1.2rem;">Community Support:</p>
                <div class="community-logos"
                    style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap; align-items: center;">
                    <!-- Logo 1 -->
                    <div class="community-logo-item"
                        style="background: white; padding: 12px; border-radius: 16px; height: 100px; display: flex; align-items: center; box-shadow: 0 6px 12px rgba(0,0,0,0.15);">
                        <img src="{{ asset('images/komunitas/Logo Craftiva.JPG.jpeg') }}" alt="Craftiva"
                            style="height: 100%; width: auto;">
                    </div>
                    <!-- Logo 2 -->
                    <div class="community-logo-item"
                        style="background: white; padding: 12px; border-radius: 16px; height: 100px; display: flex; align-items: center; box-shadow: 0 6px 12px rgba(0,0,0,0.15);">
                        <img src="{{ asset('images/komunitas/Logo Hawa Community.png') }}" alt="Hawa Community"
                            style="height: 100%; width: auto;">
                    </div>
                    <!-- Logo 3 -->
                    <div class="community-logo-item"
                        style="background: white; padding: 12px; border-radius: 16px; height: 100px; display: flex; align-items: center; box-shadow: 0 6px 12px rgba(0,0,0,0.15);">
                        <img src="{{ asset('images/komunitas/Logo Ruang Alara (1).png') }}" alt="Ruang Alara"
                            style="height: 100%; width: auto;">
                    </div>
                    <!-- Logo 4 -->
                    <div class="community-logo-item"
                        style="background: white; padding: 12px; border-radius: 16px; height: 100px; display: flex; align-items: center; box-shadow: 0 6px 12px rgba(0,0,0,0.15);">
                        <img src="{{ asset('images/komunitas/Logo.png') }}" alt="Logo"
                            style="height: 100%; width: auto;">
                    </div>
                    <!-- Logo 5 -->
                    <div class="community-logo-item"
                        style="background: white; padding: 12px; border-radius: 16px; height: 100px; display: flex; align-items: center; box-shadow: 0 6px 12px rgba(0,0,0,0.15);">
                        <img src="{{ asset('images/komunitas/logo ufairah.jpg.jpeg') }}" alt="Ufairah"
                            style="height: 100%; width: auto;">
                    </div>
                    <!-- Logo 6 -->
                    <div class="community-logo-item"
                        style="background: white; padding: 12px; border-radius: 16px; height: 100px; display: flex; align-items: center; box-shadow: 0 6px 12px rgba(0,0,0,0.15);">
                        <img src="{{ asset('images/komunitas/new gemusi.png') }}" alt="Gemusi"
                            style="height: 100%; width: auto;">
                    </div>
                    <!-- Logo 7 -->
                    <div class="community-logo-item"
                        style="background: white; padding: 12px; border-radius: 16px; height: 100px; display: flex; align-items: center; box-shadow: 0 6px 12px rgba(0,0,0,0.15);">
                        <img src="{{ asset('images/komunitas/rest area.png') }}" alt="Rest Area"
                            style="height: 100%; width: auto;">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Problem Section -->
    <section class="problem-section container">
        <div class="problem-image">
            <img src="{{ asset('images/asset/Asset-3@4x.png') }}" alt="Graduation Cap" class="problem-cap-img">
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