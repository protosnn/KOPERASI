<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Koperasi Simpan Pinjam - Beranda</title>
  <link rel="shortcut icon" type="image/png" href="/koperasi/template/assets/images/logos/favicon.svg" />
  <link rel="stylesheet" href="/koperasi/template/assets/css/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #667eea;
      --secondary: #764ba2;
      --accent: #28a745;
      --light-bg: #f8f9fa;
      --text-dark: #1a1a1a;
      --text-muted: #6c757d;
      --border-color: #e9ecef;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      color: var(--text-dark);
      line-height: 1.6;
      background: var(--light-bg);
    }

    /* NAVBAR */
    .navbar {
      background: white;
      box-shadow: 0 2px 8px rgba(0,0,0,0.08);
      padding: 16px 0;
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .navbar-inner {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .navbar-brand {
      display: flex;
      align-items: center;
      gap: 10px;
      text-decoration: none;
      font-weight: 700;
      color: var(--text-dark);
      font-size: 20px;
    }

    .navbar-brand img { height: 40px; }

    .navbar-links { display: flex; gap: 30px; align-items: center; }

    .navbar-links a {
      text-decoration: none;
      color: var(--text-muted);
      font-weight: 500;
      transition: color 0.3s;
    }

    .navbar-links a:hover { color: var(--primary); }

    /* HERO SECTION */
    .hero {
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      color: white;
      padding: 100px 20px;
      text-align: center;
    }

    .hero-content {
      max-width: 1200px;
      margin: 0 auto;
    }

    .hero h1 {
      font-size: 56px;
      font-weight: 800;
      margin-bottom: 20px;
      line-height: 1.2;
      animation: fadeInUp 0.6s ease-out;
    }

    .hero p {
      font-size: 20px;
      opacity: 0.95;
      margin-bottom: 40px;
      max-width: 700px;
      margin-left: auto;
      margin-right: auto;
      animation: fadeInUp 0.8s ease-out;
    }

    .cta-buttons {
      display: flex;
      gap: 20px;
      justify-content: center;
      flex-wrap: wrap;
      animation: fadeInUp 1s ease-out;
    }

    .btn {
      padding: 14px 32px;
      border-radius: 8px;
      text-decoration: none;
      font-weight: 600;
      transition: all 0.3s ease;
      border: none;
      cursor: pointer;
      font-size: 16px;
      display: inline-block;
    }

    .btn-primary {
      background: white;
      color: var(--primary);
    }

    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 12px 30px rgba(0,0,0,0.2); }

    .btn-secondary {
      background: transparent;
      color: white;
      border: 2px solid white;
    }

    .btn-secondary:hover { background: white; color: var(--primary); }

    /* FEATURES SECTION */
    .section {
      max-width: 1200px;
      margin: 0 auto;
      padding: 80px 20px;
    }

    .section-title {
      text-align: center;
      margin-bottom: 60px;
    }

    .section-title h2 {
      font-size: 42px;
      font-weight: 700;
      margin-bottom: 15px;
      color: var(--text-dark);
    }

    .section-title p {
      font-size: 18px;
      color: var(--text-muted);
      max-width: 500px;
      margin: 0 auto;
    }

    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
      gap: 30px;
    }

    .feature-card {
      background: white;
      padding: 40px 30px;
      border-radius: 12px;
      text-align: center;
      box-shadow: 0 4px 12px rgba(0,0,0,0.06);
      transition: all 0.3s ease;
      border-top: 4px solid var(--primary);
    }

    .feature-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 30px rgba(0,0,0,0.12);
    }

    .feature-icon {
      width: 70px;
      height: 70px;
      background: linear-gradient(135deg, var(--primary), var(--secondary));
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
      color: white;
      font-size: 32px;
    }

    .feature-card h3 { font-size: 22px; margin-bottom: 12px; color: var(--text-dark); }

    .feature-card p { color: var(--text-muted); font-size: 15px; line-height: 1.7; }

    /* STATS SECTION */
    .stats-section {
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      color: white;
      padding: 60px 20px;
    }

    .stats-grid {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 40px;
      text-align: center;
    }

    .stat-item h4 { font-size: 48px; font-weight: 800; margin-bottom: 8px; }

    .stat-item p { font-size: 16px; opacity: 0.9; }

    /* ABOUT SECTION */
    .about-section {
      background: white;
      padding: 80px 20px;
    }

    .about-content {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 50px;
      align-items: center;
    }

    .about-text h2 { font-size: 36px; margin-bottom: 20px; color: var(--text-dark); }

    .about-text p { margin-bottom: 15px; color: var(--text-muted); line-height: 1.8; }

    .about-list {
      list-style: none;
      margin-top: 20px;
    }

    .about-list li {
      padding: 12px 0;
      display: flex;
      align-items: center;
      gap: 12px;
      color: var(--text-muted);
    }

    .about-list i {
      color: var(--accent);
      font-weight: bold;
      width: 24px;
      text-align: center;
    }

    .about-image {
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      border-radius: 12px;
      padding: 40px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      min-height: 300px;
      font-size: 80px;
    }

    /* FOOTER */
    footer {
      background: var(--text-dark);
      color: white;
      padding: 60px 20px 20px;
      margin-top: 60px;
    }

    .footer-content {
      max-width: 1200px;
      margin: 0 auto;
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 40px;
      margin-bottom: 40px;
    }

    .footer-col h4 { margin-bottom: 20px; font-size: 18px; }

    .footer-col p { color: rgba(255,255,255,0.7); margin-bottom: 10px; font-size: 14px; }

    .footer-col ul { list-style: none; }

    .footer-col ul li { margin-bottom: 10px; }

    .footer-col ul li a { color: rgba(255,255,255,0.7); text-decoration: none; transition: color 0.3s; }

    .footer-col ul li a:hover { color: white; }

    .footer-bottom {
      border-top: 1px solid rgba(255,255,255,0.1);
      padding-top: 30px;
      text-align: center;
      color: rgba(255,255,255,0.6);
      font-size: 14px;
    }

    /* ANIMATIONS */
    @keyframes fadeInUp {
      from { opacity: 0; transform: translateY(30px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* DROPDOWN */
    .dropdown { position: relative; display: inline-block; }

    .dropdown-menu {
      position: absolute;
      right: 0;
      top: 50px;
      background: white;
      color: var(--text-dark);
      min-width: 200px;
      border-radius: 8px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
      display: none;
      overflow: hidden;
      z-index: 1000;
    }

    .dropdown-menu a {
      display: block;
      padding: 12px 16px;
      text-decoration: none;
      color: var(--text-dark);
      transition: background 0.3s;
    }

    .dropdown-menu a:hover { background: var(--light-bg); color: var(--primary); }

    .dropdown.open .dropdown-menu { display: block; }

    /* RESPONSIVE */
    @media (max-width: 768px) {
      .hero h1 { font-size: 36px; }
      .hero p { font-size: 18px; }
      .navbar-links { display: none; }
      .about-content { grid-template-columns: 1fr; }
      .section { padding: 60px 20px; }
      .section-title h2 { font-size: 32px; }
      .cta-buttons { flex-direction: column; }
      .btn { width: 100%; }
      .stats-grid { gap: 30px; }
      .stat-item h4 { font-size: 36px; }
    }
  </style>
</head>

<body>

  <!-- NAVBAR -->
  <nav class="navbar">
    <div class="navbar-inner">
      <a href="/koperasi/home.php" class="navbar-brand">
        <i class="fas fa-piggy-bank"></i> Koperasi Simpan Pinjam
      </a>
      <div class="navbar-links">
        <a href="#fitur">Fitur</a>
        <a href="#tentang">Tentang</a>
      </div>
    </div>
  </nav>

  <!-- HERO -->
  <section class="hero">
    <div class="hero-content">
      <h1>Kelola Koperasi Anda dengan Mudah</h1>
      <p>Platform digital untuk simpanan, pinjaman, dan manajemen anggota koperasi yang terintegrasi dan aman</p>
      <div class="cta-buttons">
        <div class="dropdown" id="dd">
          <button class="btn btn-primary" id="ddToggle">Masuk Sekarang ▾</button>
          <div class="dropdown-menu" id="ddMenu">
            <a href="/koperasi/login.php">Login Admin</a>
            <a href="/koperasi/anggota/login.php">Login Anggota</a>
          </div>
        </div>
        <a href="#fitur" class="btn btn-secondary">Pelajari Lebih Lanjut</a>
      </div>
    </div>
  </section>

  <!-- FITUR -->
  <section class="section" id="fitur">
    <div class="section-title">
      <h2>Fitur Unggulan</h2>
      <p>Solusi lengkap untuk mengelola koperasi Anda dengan efisien</p>
    </div>
    <div class="features-grid">
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-wallet"></i></div>
        <h3>Manajemen Simpanan</h3>
        <p>Pencatatan simpanan pokok, wajib, dan sukarela per anggota dengan rekap per periode.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-money-bill-wave"></i></div>
        <h3>Manajemen Pinjaman</h3>
        <p>Pengajuan, verifikasi, persetujuan, dan pelacakan angsuran pinjaman dengan mudah.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-users"></i></div>
        <h3>Kelola Anggota</h3>
        <p>Database lengkap anggota dengan riwayat transaksi dan informasi kontak yang terorganisir.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-chart-bar"></i></div>
        <h3>Laporan & Analisis</h3>
        <p>Dashboard KPI, ekspor ke Excel/PDF, dan visualisasi data untuk keputusan bisnis yang lebih baik.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-lock"></i></div>
        <h3>Keamanan Data</h3>
        <p>Autentikasi terpercaya dan kontrol akses yang ketat untuk melindungi data koperasi Anda.</p>
      </div>
      <div class="feature-card">
        <div class="feature-icon"><i class="fas fa-mobile-alt"></i></div>
        <h3>Responsif & Modern</h3>
        <p>Interface modern yang berfungsi sempurna di desktop, tablet, dan perangkat mobile.</p>
      </div>
    </div>
  </section>

  <!-- STATS -->
  <section class="stats-section">
    <div class="stats-grid">
      <div class="stat-item">
        <h4>100%</h4>
        <p>Data Aman</p>
      </div>
      <div class="stat-item">
        <h4>24/7</h4>
        <p>Akses Sistem</p>
      </div>
      <div class="stat-item">
        <h4>5+</h4>
        <p>Fitur Utama</p>
      </div>
      <div class="stat-item">
        <h4>&infin;</h4>
        <p>Skalabilitas</p>
      </div>
    </div>
  </section>

  <!-- ABOUT -->
  <section class="section" id="tentang">
    <div class="about-content">
      <div class="about-text">
        <h2>Tentang Aplikasi Kami</h2>
        <p>Aplikasi Koperasi Simpan Pinjam dirancang khusus untuk memenuhi kebutuhan manajemen modern dari koperasi Indonesia. Dengan teknologi terkini dan interface yang user-friendly, kami membantu koperasi Anda berkembang lebih cepat.</p>
        <ul class="about-list">
          <li><i class="fas fa-check-circle"></i> <strong>Mudah digunakan</strong> – Interface intuitif untuk semua pengguna</li>
          <li><i class="fas fa-check-circle"></i> <strong>Terintegrasi</strong> – Semua data terhubung dalam satu sistem</li>
          <li><i class="fas fa-check-circle"></i> <strong>Laporan otomatis</strong> – Generate laporan dengan sekali klik</li>
          <li><i class="fas fa-check-circle"></i> <strong>Support profesional</strong> – Tim siap membantu 24/7</li>
        </ul>
      </div>
      <div class="about-image">
        <i class="fas fa-handshake"></i>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <footer>
    <div class="footer-content">
      <div class="footer-col">
        <h4>Koperasi Simpan Pinjam</h4>
        <p>Platform manajemen koperasi digital yang terpercaya dan profesional.</p>
      </div>
      <div class="footer-col">
        <h4>Menu</h4>
        <ul>
          <li><a href="/koperasi/home.php">Beranda</a></li>
          <li><a href="#fitur">Fitur</a></li>
          <li><a href="#tentang">Tentang</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Akses</h4>
        <ul>
          <li><a href="/koperasi/login.php">Login Admin</a></li>
          <li><a href="/koperasi/anggota/login.php">Login Anggota</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Kontak</h4>
        <p>Email: info@koperasi.local</p>
        <p>Telepon: +62 XXX XXXX XXXX</p>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 Koperasi Simpan Pinjam. Semua hak dilindungi.</p>
    </div>
  </footer>

  <script>
    // Dropdown toggle
    const dd = document.getElementById('dd');
    const toggle = document.getElementById('ddToggle');
    toggle.addEventListener('click', (e) => {
      e.preventDefault();
      dd.classList.toggle('open');
    });
    document.addEventListener('click', (e) => {
      if (!dd.contains(e.target)) dd.classList.remove('open');
    });

    // Smooth scroll
    document.querySelectorAll('a[href^="#"]').forEach(a => {
      a.addEventListener('click', (e) => {
        const href = a.getAttribute('href');
        if (href !== '#') {
          e.preventDefault();
          const target = document.querySelector(href);
          if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
            dd.classList.remove('open');
          }
        }
      });
    });
  </script>

</body>

</html>
