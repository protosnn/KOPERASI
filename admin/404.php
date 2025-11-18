<!DOCTYPE html>
<html lang="en">
<?php 
// Handle 404 error - bisa dipanggil dari berbagai lokasi
$current_page = isset($_GET['page']) ? htmlspecialchars($_GET['page']) : 'Halaman tidak ditemukan';
$requested_url = isset($_SERVER['REQUEST_URI']) ? htmlspecialchars($_SERVER['REQUEST_URI']) : 'URL tidak diketahui';
?>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Koperasi - 404 Not Found</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="/koperasi/template2/vendors/feather/feather.css">
  <link rel="stylesheet" href="/koperasi/template2/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="/koperasi/template2/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="/koperasi/template2/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <style>
    body {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .error-container {
      background: white;
      border-radius: 15px;
      box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
      padding: 60px 40px;
      max-width: 600px;
      text-align: center;
      animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .error-code {
      font-size: 120px;
      font-weight: bold;
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      margin: 0;
      line-height: 1;
    }

    .error-message {
      font-size: 28px;
      color: #333;
      margin: 20px 0;
      font-weight: 600;
    }

    .error-description {
      font-size: 16px;
      color: #666;
      margin: 20px 0 30px 0;
      line-height: 1.6;
    }

    .error-details {
      background: #f5f5f5;
      border-left: 4px solid #667eea;
      padding: 15px 20px;
      text-align: left;
      border-radius: 5px;
      margin: 30px 0;
      font-size: 14px;
      color: #555;
    }

    .error-details p {
      margin: 5px 0;
    }

    .error-icon {
      font-size: 80px;
      color: #667eea;
      margin-bottom: 20px;
    }

    .btn-group-404 {
      display: flex;
      gap: 15px;
      margin-top: 30px;
      justify-content: center;
      flex-wrap: wrap;
    }

    .btn-404 {
      padding: 12px 30px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      cursor: pointer;
      text-decoration: none;
      display: inline-block;
      transition: all 0.3s ease;
      font-weight: 600;
    }

    .btn-primary-404 {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
    }

    .btn-primary-404:hover {
      transform: translateY(-2px);
      box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
      color: white;
      text-decoration: none;
    }

    .btn-secondary-404 {
      background: #f0f0f0;
      color: #333;
      border: 1px solid #ddd;
    }

    .btn-secondary-404:hover {
      background: #e0e0e0;
      text-decoration: none;
    }

    .breadcrumb-404 {
      margin-top: 30px;
      padding-top: 30px;
      border-top: 1px solid #eee;
      font-size: 14px;
      color: #999;
    }

    .suggestion-links {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
      margin-top: 20px;
    }

    .suggestion-link {
      padding: 10px;
      background: #f9f9f9;
      border: 1px solid #eee;
      border-radius: 5px;
      text-decoration: none;
      color: #667eea;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .suggestion-link:hover {
      background: #f0f7ff;
      border-color: #667eea;
      transform: translateX(5px);
    }

    @media (max-width: 768px) {
      .error-container {
        padding: 40px 20px;
      }

      .error-code {
        font-size: 80px;
      }

      .error-message {
        font-size: 24px;
      }

      .btn-group-404 {
        flex-direction: column;
      }

      .btn-404 {
        width: 100%;
      }

      .suggestion-links {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>

<body>
  <div class="error-container">
    <div class="error-icon">
      <i class="ti-alert"></i>
    </div>

    <h1 class="error-code">404</h1>
    <h2 class="error-message">Halaman Tidak Ditemukan</h2>
    <p class="error-description">
      Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan ke lokasi lain.
    </p>

    <div class="error-details">
      <p><strong>URL yang diminta:</strong> <?php echo $requested_url; ?></p>
      <p><strong>Waktu:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>
    </div>

    <div class="btn-group-404">
      <a href="/koperasi/index.php" class="btn-404 btn-primary-404">
        <i class="ti-home"></i> Kembali ke Login
      </a>
      <a href="javascript:history.back()" class="btn-404 btn-secondary-404">
        <i class="ti-arrow-left"></i> Kembali
      </a>
    </div>

    <div class="suggestion-links">
      <strong style="grid-column: 1/-1; margin-top: 20px;">Navigasi Cepat:</strong>
      <a href="/koperasi/admin/dashboard.php" class="suggestion-link">
        <i class="ti-dashboard"></i> Dashboard
      </a>
      <a href="/koperasi/admin/anggota.php" class="suggestion-link">
        <i class="ti-user"></i> Anggota
      </a>
      <a href="/koperasi/admin/pinjaman/pinjaman.php" class="suggestion-link">
        <i class="ti-money"></i> Pinjaman
      </a>
      <a href="/koperasi/admin/pemasukan/simpanan.php" class="suggestion-link">
        <i class="ti-wallet"></i> Simpanan
      </a>
    </div>

    <div class="breadcrumb-404">
      <p>Jika masalah terus berlanjut, hubungi <strong>administrator@koperasi.local</strong></p>
    </div>
  </div>

  <!-- Scripts -->
  <script src="/koperasi/template2/vendors/js/vendor.bundle.base.js"></script>
  <script>
    // Log error untuk debugging
    console.log('404 Error - Requested URL: ' + '<?php echo $requested_url; ?>');
    
    // Optional: Send error to server for monitoring
    // fetch('/proses/log_error.php', {
    //   method: 'POST',
    //   headers: {
    //     'Content-Type': 'application/json'
    //   },
    //   body: JSON.stringify({
    //     error_type: '404',
    //     requested_url: '<?php echo $requested_url; ?>',
    //     timestamp: new Date().toISOString()
    //   })
    // });
  </script>
</body>

</html>
