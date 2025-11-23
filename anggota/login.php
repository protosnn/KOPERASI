<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Koperasi Simpan Pinjam - Login Anggota</title>
  <link rel="shortcut icon" type="image/png" href="../template/assets/images/logos/favicon.svg" />
  <link rel="stylesheet" href="../template/assets/libs/owl.carousel/dist/assets/owl.carousel.min.css">
  <link rel="stylesheet" href="../template/assets/libs/aos-master/dist/aos.css">
  <link rel="stylesheet" href="../template/assets/css/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #667eea;
      --secondary-color: #764ba2;
      --success-color: #28a745;
    }

    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .page-wrapper {
      min-height: 100vh;
    }

    .login-section {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 20px;
    }

    .login-container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
      align-items: center;
      max-width: 1000px;
      width: 100%;
    }

    .login-info {
      color: white;
      animation: slideInLeft 0.6s ease-out;
    }

    .login-info h1 {
      font-size: 42px;
      font-weight: 700;
      margin-bottom: 20px;
      line-height: 1.3;
    }

    .login-info .tagline {
      font-size: 18px;
      opacity: 0.95;
      margin-bottom: 30px;
      line-height: 1.6;
    }

    .feature-list {
      list-style: none;
      padding: 0;
      margin: 0;
    }

    .feature-list li {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
      font-size: 16px;
    }

    .feature-list i {
      width: 35px;
      height: 35px;
      background: rgba(255, 255, 255, 0.2);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 12px;
      color: white;
    }

    .login-form-wrapper {
      animation: slideInRight 0.6s ease-out;
    }

    .sign-in {
      background: white;
      border-radius: 15px;
      box-shadow: 0 30px 60px rgba(0, 0, 0, 0.3);
      border: none;
    }

    .card-body {
      padding: 50px 40px !important;
    }

    .logo-section {
      text-align: center;
      margin-bottom: 30px;
    }

    .logo-section img {
      max-height: 60px;
      margin-bottom: 10px;
    }

    .logo-title {
      font-size: 24px;
      font-weight: 700;
      color: #333;
      margin: 10px 0 5px 0;
    }

    .logo-subtitle {
      font-size: 14px;
      color: #666;
    }

    .role-badge {
      display: inline-block;
      background: #e3f2fd;
      color: #1976d2;
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
      margin-bottom: 15px;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-control {
      border: none !important;
      border-bottom: 2px solid #e0e0e0 !important;
      border-radius: 0 !important;
      padding: 12px 0 !important;
      font-size: 16px;
      transition: all 0.3s ease;
    }

    .form-control:focus {
      border-bottom-color: #667eea !important;
      box-shadow: none !important;
      background: transparent;
    }

    .form-control::placeholder {
      color: #999;
    }

    .btn-login {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      padding: 12px 30px;
      font-size: 16px;
      font-weight: 600;
      border-radius: 8px;
      transition: all 0.3s ease;
      margin-top: 20px;
      margin-bottom: 20px;
    }

    .btn-login:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
      color: white;
    }

    .form-footer {
      text-align: center;
      margin-top: 25px;
      border-top: 1px solid #eee;
      padding-top: 20px;
    }

    .form-footer a {
      color: #667eea;
      font-weight: 500;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .form-footer a:hover {
      color: #764ba2;
      text-decoration: underline;
    }

    .form-footer p {
      margin: 0;
      font-size: 14px;
      color: #666;
    }

    @keyframes slideInLeft {
      from {
        opacity: 0;
        transform: translateX(-30px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes slideInRight {
      from {
        opacity: 0;
        transform: translateX(30px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @media (max-width: 768px) {
      .login-container {
        grid-template-columns: 1fr;
        gap: 30px;
      }

      .login-info {
        text-align: center;
      }

      .login-info h1 {
        font-size: 32px;
      }

      .card-body {
        padding: 30px 20px !important;
      }

      .feature-list {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 20px;
      }

      .feature-list li {
        margin-bottom: 0;
      }
    }

    .scroll-top-btn {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      border: none;
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .scroll-top-btn:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }
  </style>
</head>

<body>

  <!--  Page Wrapper -->
  <div class="page-wrapper overflow-hidden">

    <!--  Login Section -->
    <section class="login-section">
      <div class="login-container">
        <!-- Left Side - Info -->
        <div class="login-info">
          <h1>Selamat Datang Anggota</h1>
          <p class="tagline">Kelola akun koperasi Anda dengan mudah dan lihat status simpanan serta pinjaman Anda.</p>
          
          <ul class="feature-list">
            <li>
              <i class="fas fa-wallet"></i>
              <span>Lihat Saldo Simpanan</span>
            </li>
            <li>
              <i class="fas fa-money-bill-wave"></i>
              <span>Pantau Status Pinjaman</span>
            </li>
            <li>
              <i class="fas fa-calendar-alt"></i>
              <span>Riwayat Transaksi & Pembayaran</span>
            </li>
            <li>
              <i class="fas fa-lock"></i>
              <span>Data Aman & Terlindungi</span>
            </li>
          </ul>
        </div>

        <!-- Right Side - Form -->
        <div class="login-form-wrapper">
          <div class="sign-in card">
            <div class="card-body">
              <!-- Logo & Title -->
              <div class="logo-section">
                <img src="../template/assets/images/logos/logo-dark.svg" alt="logo-koperasi">
                <div class="logo-title">Koperasi Simpan Pinjam</div>
                <div class="logo-subtitle">Portal Anggota</div>
              </div>

              <!-- Role Badge -->
              <div style="text-align: center; margin-bottom: 15px;">
                <span class="role-badge"><i class="fas fa-user-circle"></i> Login Anggota</span>
              </div>

              <!-- Login Form -->
              <form action="proses_login_anggota.php" method="POST">
                <div class="form-group">
                  <input type="text" name="username" class="form-control" placeholder="Username atau Email" required>
                </div>
                <div class="form-group">
                  <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>

                <button type="submit" class="btn btn-login w-100">
                  <i class="fas fa-sign-in-alt"></i> Masuk
                </button>
              </form>

              <!-- Footer -->
              <div class="form-footer">
                <a href="#forgot-password" style="display: block; margin-bottom: 10px;">
                  <i class="fas fa-question-circle"></i> Lupa Password?
                </a>
                <p>
                  Belum terdaftar? <a href="../regristasi.php">Daftar di sini</a>
                </p>
                <p>
                  <a href="../home.php">← Kembali ke Beranda</a>
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

  </div>

  <!-- Scroll to Top Button -->
  <button class="scroll-top-btn" id="scrollToTopBtn" style="position: fixed; bottom: 30px; right: 30px; z-index: 99; display: none;">
    <i class="fas fa-arrow-up" style="color: white;"></i>
  </button>

  <script src="../template/assets/libs/jquery/dist/jquery.min.js"></script>
  <script src="../template/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../template/assets/libs/owl.carousel/dist/owl.carousel.min.js"></script>
  <script src="../template/assets/libs/aos-master/dist/aos.js"></script>
  <script src="../template/assets/js/custom.js"></script>
  <!-- Iconify for modern icons -->
  <script src="https://cdn.jsdelivr.net/npm/iconify-icon@1.0.8/dist/iconify-icon.min.js"></script>
  
  <script>
    // Scroll to top functionality
    const scrollTopBtn = document.getElementById('scrollToTopBtn');
    
    window.addEventListener('scroll', () => {
      if (window.pageYOffset > 300) {
        scrollTopBtn.style.display = 'flex';
      } else {
        scrollTopBtn.style.display = 'none';
      }
    });

    scrollTopBtn.addEventListener('click', () => {
      window.scrollTo({
        top: 0,
        behavior: 'smooth'
      });
    });

    // Add animation on page load
    document.addEventListener('DOMContentLoaded', () => {
      AOS.init({
        duration: 1000,
        once: true,
      });
    });
  </script>
</body>

</html>
