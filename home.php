<?php
// Home page - informasi aplikasi Koperasi Simpan Pinjam
?>
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Koperasi Simpan Pinjam - Beranda</title>
  <link rel="shortcut icon" type="image/png" href="/koperasi/template/assets/images/logos/favicon.svg" />
  <link rel="stylesheet" href="/koperasi/template/assets/css/styles.css" />
  <style>
    :root { --primary:#667eea; --secondary:#764ba2; --muted:#6c757d; }
    body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg,var(--primary) 0%,var(--secondary) 100%); color:#fff; margin:0; }
    .wrap { max-width:1100px; margin:60px auto; padding:28px; }
    .card { background: rgba(255,255,255,0.06); border-radius:12px; padding:28px; box-shadow: 0 20px 50px rgba(0,0,0,0.25); }
    .brand { display:flex; gap:18px; align-items:center; }
    .brand img { height:56px; }
    .brand h1 { font-size:24px; margin:0; }
    .lead { color:rgba(255,255,255,0.95); margin-top:6px; }
    .grid { display:grid; grid-template-columns: 2fr 1fr; gap:22px; margin-top:22px; }
    .section { background:transparent; padding:0; }
    h2 { color:#fff; margin-top:0; }
    p { color:rgba(255,255,255,0.9); }
    .list { display:grid; gap:12px; margin-top:12px; }
    .item { background: rgba(255,255,255,0.03); padding:12px; border-radius:8px; }
    .right { text-align:center; display:flex; flex-direction:column; justify-content:center; gap:12px; }
    .btn { background: linear-gradient(135deg,var(--primary),var(--secondary)); color:#fff; padding:10px 16px; border-radius:8px; text-decoration:none; display:inline-block; }
    .btn-outline { background:transparent; border:1px solid rgba(255,255,255,0.18); color:#fff; padding:10px 14px; border-radius:8px; text-decoration:none; }
    .dropdown { position:relative; display:inline-block; }
    .dropdown-menu { position:absolute; right:0; top:44px; background:#fff; color:#333; min-width:180px; border-radius:8px; box-shadow:0 10px 30px rgba(0,0,0,0.2); display:none; overflow:hidden; }
    .dropdown-menu a{ display:block; padding:10px 14px; text-decoration:none; color:#333; }
    .dropdown.open .dropdown-menu{ display:block; }
    @media(max-width:900px){ .grid{ grid-template-columns:1fr; } .right{ order:-1 } }
  </style>
</head>
<body>
  <div class="wrap">
    <div class="card">
      <div class="brand">
        <img src="/koperasi/template/assets/images/logos/logo-dark.svg" alt="logo">
        <div>
          <h1>Koperasi Simpan Pinjam</h1>
          <div class="lead">Sistem manajemen koperasi untuk mengelola simpanan, pinjaman, anggota, dan laporan.</div>
        </div>
      </div>

      <div class="grid">
        <div class="section">
          <h2>Tentang Aplikasi</h2>
          <p>Aplikasi <strong>Koperasi Simpan Pinjam</strong> ini dibuat untuk memudahkan pengelolaan administrasi koperasi, meliputi:</p>
          <div class="list">
            <div class="item"><strong>Manajemen Simpanan:</strong> Pencatatan simpanan pokok, wajib, dan sukarela per anggota serta rekap per periode.</div>
            <div class="item"><strong>Manajemen Pinjaman:</strong> Pengajuan, verifikasi, persetujuan (ACC), pencatatan tenor, dan pelacakan angsuran.</div>
            <div class="item"><strong>Angsuran & Pembayaran:</strong> Pencatatan bayar angsuran, status lunas, serta histori transaksi.</div>
            <div class="item"><strong>Export & Reporting:</strong> Ekspor data ke Excel/PDF, cetak rekap, dan dashboard KPI.</div>
            <div class="item"><strong>Keamanan & Akses:</strong> Otentikasi sederhana untuk Admin dan Anggota; kontrol akses dasar.</div>
          </div>

          <h2 style="margin-top:20px;">Panduan Singkat</h2>
          <p>Untuk memulai: admin dapat login untuk mengelola data anggota, menyetujui pinjaman, dan melihat rekap keuangan. Anggota dapat login untuk melihat saldo simpanan dan status pinjaman.</p>
        </div>

        <div class="right">
          <div style="background:linear-gradient(135deg,#ffffff14,#ffffff06); padding:18px; border-radius:10px;">
            <h3 style="color:#fff; margin:0 0 8px 0;">Masuk</h3>
            <p style="color:rgba(255,255,255,0.9); margin:0 0 12px 0;">Pilih jenis akun untuk masuk</p>
            <div class="dropdown" id="dd">
              <a href="#" class="btn" id="ddToggle">Login ▾</a>
              <div class="dropdown-menu" id="ddMenu">
                <a href="/koperasi/login.php">Login Admin</a>
                <a href="/koperasi/anggota/login.php">Login Anggota</a>
              </div>
            </div>
          </div>

          <div style="margin-top:16px; text-align:left;">
            <a class="btn-outline" href="/koperasi/admin/404.php">Dokumentasi Singkat</a>
          </div>
        </div>
      </div>

    </div>
  </div>

  <script>
    const dd = document.getElementById('dd');
    const toggle = document.getElementById('ddToggle');
    toggle.addEventListener('click', (e)=>{ e.preventDefault(); dd.classList.toggle('open'); });
    document.addEventListener('click', (e)=>{ if(!dd.contains(e.target)) dd.classList.remove('open'); });
  </script>
</body>
</html>
