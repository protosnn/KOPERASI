<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Koneksi database
$host = "localhost";
$username = "root";
$password = "";
$database = "koperasi";

$koneksi = mysqli_connect($host, $username, $password, $database);
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Initialize variables
$data_rekap = [
    'total_anggota' => 0,
    'total_simpanan' => 0,
    'pinjaman_acc' => 0,
    'angsuran_lunas' => 0,
    'bunga_lunas' => 0,
    'saldo_akhir' => 0
];

$data_transaksi_simpanan = [];
$data_transaksi_pinjaman = [];
$data_transaksi_angsuran = [];

try {
    // 1. GET TOTAL ANGGOTA
    $anggota_query = "SELECT COUNT(*) as total FROM anggota";
    $result_anggota = mysqli_query($koneksi, $anggota_query);
    if ($result_anggota) {
        $anggota_data = mysqli_fetch_assoc($result_anggota);
        $data_rekap['total_anggota'] = $anggota_data['total'];
    }

    // 2. GET TOTAL SIMPANAN (semua data tanpa filter bulan)
    $simpanan_query = "SELECT COALESCE(SUM(nominal), 0) as total FROM simpanan";
    
    $result_simpanan = mysqli_query($koneksi, $simpanan_query);
    if ($result_simpanan) {
        $simpanan_data = mysqli_fetch_assoc($result_simpanan);
        $data_rekap['total_simpanan'] = $simpanan_data['total'];
    }

    // 3. GET PINJAMAN ACC (semua data tanpa filter bulan)
    $pinjaman_query = "SELECT COALESCE(SUM(jumlah_pinjaman), 0) as total FROM pinjaman WHERE status = 'acc'";
    
    $result_pinjaman = mysqli_query($koneksi, $pinjaman_query);
    if ($result_pinjaman) {
        $pinjaman_data = mysqli_fetch_assoc($result_pinjaman);
        $data_rekap['pinjaman_acc'] = $pinjaman_data['total'];
    }

    // 4. GET ANGSURAN LUNAS (semua data tanpa filter bulan)
    $angsuran_query = "SELECT 
                      COALESCE(SUM(nominal), 0) as angsuran,
                      COALESCE(SUM(bunga), 0) as bunga
                      FROM angsuran WHERE status = 'lunas'";
    
    $result_angsuran = mysqli_query($koneksi, $angsuran_query);
    if ($result_angsuran) {
        $angsuran_data = mysqli_fetch_assoc($result_angsuran);
        $data_rekap['angsuran_lunas'] = $angsuran_data['angsuran'];
        $data_rekap['bunga_lunas'] = $angsuran_data['bunga'];
    }
    
    // Calculate saldo akhir
    $data_rekap['saldo_akhir'] = ($data_rekap['total_simpanan'] + $data_rekap['angsuran_lunas'] + $data_rekap['bunga_lunas']) - $data_rekap['pinjaman_acc'];
    
    // 5. HITUNG SHU (Sisa Hasil Usaha) - BERBASIS BUNGA ANGSURAN (KEUNTUNGAN MURNI)
    // SHU = Bunga Angsuran Lunas (keuntungan dari pinjaman)
    // Dibagi merata kepada semua anggota
    $data_rekap['shu_total'] = $data_rekap['bunga_lunas'];
    
    // Hitung jumlah anggota aktif
    $jumlah_anggota = $data_rekap['total_anggota'];
    
    // SHU per anggota = Total Bunga / Jumlah Anggota
    if($jumlah_anggota > 0) {
        $data_rekap['shu_per_anggota'] = $data_rekap['shu_total'] / $jumlah_anggota;
    } else {
        $data_rekap['shu_per_anggota'] = 0;
    }
    
    // 6. SIAPKAN DATA UNTUK TABEL SHU PER ANGGOTA
    $shu_per_anggota = [];
    
    // Get all anggota untuk ditampilkan di tabel SHU
    $anggota_query_all = "SELECT id, nama FROM anggota ORDER BY nama ASC";
    $result_anggota_all = mysqli_query($koneksi, $anggota_query_all);
    
    while($anggota = mysqli_fetch_assoc($result_anggota_all)) {
        $shu_per_anggota[$anggota['id']] = [
            'nama' => $anggota['nama'],
            'shu_perolehan' => $data_rekap['shu_per_anggota']  // Sama untuk semua anggota
        ];
    }
    
    // 7. GET DETAILED TRANSACTION DATA - SIMPANAN (semua data tanpa filter bulan)
    $transaksi_simpanan_query = "SELECT 
                                s.tanggal,
                                a.nama as nama_anggota,
                                s.nominal
                                FROM simpanan s
                                LEFT JOIN anggota a ON s.anggota_id = a.id
                                ORDER BY s.tanggal DESC, s.id DESC";
    
    $result_simpanan_detail = mysqli_query($koneksi, $transaksi_simpanan_query);
    if ($result_simpanan_detail) {
        while ($row = mysqli_fetch_assoc($result_simpanan_detail)) {
            $data_transaksi_simpanan[] = $row;
        }
    }

    // 6. GET DETAILED TRANSACTION DATA - PINJAMAN (semua data tanpa filter bulan)
    $transaksi_pinjaman_query = "SELECT 
                                p.tanggal_pengajuan,
                                p.tanggal_acc,
                                a.nama as nama_anggota,
                                p.jumlah_pinjaman,
                                p.status
                                FROM pinjaman p
                                LEFT JOIN anggota a ON p.anggota_id = a.id
                                WHERE p.status = 'acc'
                                ORDER BY p.tanggal_acc DESC, p.id DESC";
    
    $result_pinjaman_detail = mysqli_query($koneksi, $transaksi_pinjaman_query);
    if ($result_pinjaman_detail) {
        while ($row = mysqli_fetch_assoc($result_pinjaman_detail)) {
            $data_transaksi_pinjaman[] = $row;
        }
    }

    // 7. GET DETAILED TRANSACTION DATA - ANGSURAN (semua data tanpa filter bulan)
    $transaksi_angsuran_query = "SELECT 
                                ag.tgl_pelunasan,
                                a.nama as nama_anggota,
                                ag.angsuran_ke,
                                ag.nominal,
                                ag.bunga,
                                ag.status
                                FROM angsuran ag
                                LEFT JOIN pinjaman p ON ag.pinjaman_id = p.id
                                LEFT JOIN anggota a ON p.anggota_id = a.id
                                WHERE ag.status = 'lunas'
                                ORDER BY ag.tgl_pelunasan DESC, ag.id DESC";
    
    $result_angsuran_detail = mysqli_query($koneksi, $transaksi_angsuran_query);
    if ($result_angsuran_detail) {
        while ($row = mysqli_fetch_assoc($result_angsuran_detail)) {
            $data_transaksi_angsuran[] = $row;
        }
    }
    
} catch (Exception $e) {
    error_log("Database error: " . $e->getMessage());
}
?>
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Koperasi - Rekap Bulanan</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../template2/vendors/feather/feather.css">
  <link rel="stylesheet" href="../template2/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" href="../template2/vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- Plugin css for this page -->
  <link rel="stylesheet" href="../template2/vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">
  <link rel="stylesheet" href="../template2/vendors/ti-icons/css/themify-icons.css">
  <link rel="stylesheet" type="text/css" href="../template2/js/select.dataTables.min.css">
  <style>
    .badge {
      padding: 6px 10px;
      font-size: 12px;
    }
    .badge-success {
      background-color: #00d25b;
    }
    .badge-warning {
      background-color: #ffab00;
    }
    .btn i {
      font-size: 12px;
    }
    .summary-card {
      border-left: 4px solid #4CAF50;
      background: #f8fff8;
      margin-bottom: 20px;
    }
    .summary-card.warning {
      border-left: 4px solid #ff9800;
      background: #fffaf2;
    }
    .summary-card.danger {
      border-left: 4px solid #f44336;
      background: #fff5f5;
    }
    .summary-card.info {
      border-left: 4px solid #2196F3;
      background: #f8fdff;
    }
    .total-row {
      background-color: #e8f5e8 !important;
      font-weight: bold;
    }
    .table th {
      background-color: #f8f9fa;
    }
    .text-right {
      text-align: right;
    }
    .card {
      margin-bottom: 30px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    /* SHU Table Styling */
    #tabelSHU {
      width: 100%;
      border-collapse: collapse;
      margin: 20px 0;
    }
    #tabelSHU thead th {
      background-color: #f8f9fa;
      font-weight: bold;
      padding: 15px 12px;
      border: 1px solid #dee2e6;
      text-align: left;
    }
    #tabelSHU tbody td {
      padding: 14px 12px;
      border: 1px solid #dee2e6;
    }
    #tabelSHU tbody tr:hover {
      background-color: #f8f9fa;
    }
    #tabelSHU .total-row td {
      padding: 16px 12px;
      font-weight: bold;
    }
    .shu-search-container {
      margin-bottom: 15px;
    }
    .shu-search-container input {
      padding: 8px 12px;
      border: 1px solid #dee2e6;
      border-radius: 4px;
      width: 300px;
    }
    @media print {
      .no-print {
        display: none !important;
      }
      .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
      }
    }
  </style>
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../template2/css/vertical-layout-light/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../template2/images/favicon.png" />
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.html -->
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row no-print">
      <div class="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a class="navbar-brand brand-logo mr-5" href="index.html"><img src="../template2/images/logo.svg" class="mr-2" alt="logo"/></a>
        <a class="navbar-brand brand-logo-mini" href="index.html"><img src="../template2/images/logo-mini.svg" alt="logo"/></a>
      </div>
      <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
          <span class="icon-menu"></span>
        </button>
        <ul class="navbar-nav navbar-nav-right">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="profileDropdown">
              <img src="../template2/images/faces/face28.jpg" alt="profile"/>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" aria-labelledby="profileDropdown">
              <a class="dropdown-item">
                <i class="ti-settings text-primary"></i>
                Settings
              </a>
              <a href="../logout.php" class="dropdown-item">
                <i class="ti-power-off text-primary"></i>
                Logout
              </a>
            </div>
          </li>    
        </ul>
        <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="icon-menu"></span>
        </button>
      </div>
    </nav>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_settings-panel.html -->
      <div class="theme-setting-wrapper no-print">
        <div id="settings-trigger"><i class="ti-settings"></i></div>
        <div id="theme-settings" class="settings-panel">
          <i class="settings-close ti-close"></i>
          <p class="settings-heading">SIDEBAR SKINS</p>
          <div class="sidebar-bg-options selected" id="sidebar-light-theme"><div class="img-ss rounded-circle bg-light border mr-3"></div>Light</div>
          <div class="sidebar-bg-options" id="sidebar-dark-theme"><div class="img-ss rounded-circle bg-dark border mr-3"></div>Dark</div>
          <p class="settings-heading mt-2">HEADER SKINS</p>
          <div class="color-tiles mx-0 px-4">
            <div class="tiles success"></div>
            <div class="tiles warning"></div>
            <div class="tiles danger"></div>
            <div class="tiles info"></div>
            <div class="tiles dark"></div>
            <div class="tiles default"></div>
          </div>
        </div>
      </div>
      <!-- partial:setting_pannel -->

      <!-- sidebar -->
      <?php include '../layout/sidebar.php'; ?>

      <!-- Main Panel -->
      <div class="main-panel">
        <div class="content-wrapper">
          <!-- Header -->
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-12 mb-2 mb-xl-0">
                  <h1 class="font-weight-bold">Rekap Koperasi</h1>
                  <h6 class="font-weight-normal mb-0">Dashboard Rekapitulasi Keuangan Keseluruhan</h6>
                </div>
              </div>
            </div>
          </div>

          <!-- Summary Cards -->
          <div class="row">
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card summary-card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <p class="card-title mb-0">Total Anggota</p>
                      <h3 class="text-success"><?= number_format($data_rekap['total_anggota'], 0, ',', '.') ?></h3>
                    </div>
                    <div class="icon-box">
                      <i class="ti-user text-success" style="font-size: 2rem;"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card summary-card info">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <p class="card-title mb-0">Total Simpanan</p>
                      <h3 class="text-info">Rp <?= number_format($data_rekap['total_simpanan'], 0, ',', '.') ?></h3>
                    </div>
                    <div class="icon-box">
                      <i class="ti-wallet text-info" style="font-size: 2rem;"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card summary-card warning">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <p class="card-title mb-0">Pinjaman ACC</p>
                      <h3 class="text-warning">Rp <?= number_format($data_rekap['pinjaman_acc'], 0, ',', '.') ?></h3>
                    </div>
                    <div class="icon-box">
                      <i class="ti-money text-warning" style="font-size: 2rem;"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card summary-card <?= $data_rekap['saldo_akhir'] < 0 ? 'danger' : '' ?>">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <p class="card-title mb-0">Saldo Akhir</p>
                      <h3 class="<?= $data_rekap['saldo_akhir'] < 0 ? 'text-danger' : 'text-primary' ?>">
                        Rp <?= number_format($data_rekap['saldo_akhir'], 0, ',', '.') ?>
                      </h3>
                    </div>
                    <div class="icon-box">
                      <i class="ti-stats-up <?= $data_rekap['saldo_akhir'] < 0 ? 'text-danger' : 'text-primary' ?>" style="font-size: 2rem;"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tabel Rekap -->
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Rekap Keseluruhan</h4>
                    <div id="rekapButtons"></div>
                  </div>
                  <div class="table-responsive">
                    <table id="tabelRekap" class="table table-bordered table-hover">
                      <thead class="thead-dark">
                        <tr>
                          <th>Keterangan</th>
                          <th class="text-right">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Total Anggota</td>
                          <td class="text-right" data-sort="<?= $data_rekap['total_anggota'] ?>"><?= number_format($data_rekap['total_anggota'], 0, ',', '.') ?> orang</td>
                        </tr>
                        <tr>
                          <td>Total Simpanan</td>
                          <td class="text-right" data-sort="<?= $data_rekap['total_simpanan'] ?>">Rp <?= number_format($data_rekap['total_simpanan'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                          <td>Pinjaman ACC</td>
                          <td class="text-right" data-sort="<?= $data_rekap['pinjaman_acc'] ?>">Rp <?= number_format($data_rekap['pinjaman_acc'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                          <td>Angsuran Lunas</td>
                          <td class="text-right" data-sort="<?= $data_rekap['angsuran_lunas'] ?>">Rp <?= number_format($data_rekap['angsuran_lunas'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                          <td>Bunga Lunas</td>
                          <td class="text-right" data-sort="<?= $data_rekap['bunga_lunas'] ?>">Rp <?= number_format($data_rekap['bunga_lunas'], 0, ',', '.') ?></td>
                        </tr>
                        <tr class="table-info">
                          <td><strong>SHU (Keuntungan dari Bunga)</strong></td>
                          <td class="text-right"><strong>Rp <?= number_format($data_rekap['shu_total'], 0, ',', '.') ?></strong></td>
                        </tr>
                        <tr class="table-warning">
                          <td style="padding-left: 30px;"><em>└─ SHU per Anggota (Merata)</em></td>
                          <td class="text-right"><strong>Rp <?= number_format($data_rekap['shu_per_anggota'], 0, ',', '.') ?></strong></td>
                        </tr>
                        <tr class="table-primary total-row">
                          <td><strong>Saldo Akhir</strong></td>
                          <td class="text-right"><strong>Rp <?= number_format($data_rekap['saldo_akhir'], 0, ',', '.') ?></strong></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tabel Detail Simpanan -->
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Detail Transaksi Simpanan</h4>
                    <div class="no-print">
                      <button onclick="printTable()" class="btn btn-primary btn-sm">
                        <i class="ti-printer"></i> Print
                      </button>
                      <button onclick="exportToExcel()" class="btn btn-success btn-sm">
                        <i class="ti-file"></i> Excel
                      </button>
                    </div>
                  </div>
                  
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                      <thead class="thead-dark">
                        <tr>
                          <th>No</th>
                          <th>Tanggal</th>
                          <th>Nama Anggota</th>
                          <th class="text-right">Nominal</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (count($data_transaksi_simpanan) > 0): ?>
                          <?php $no = 1; $total_simpanan = 0; ?>
                          <?php foreach ($data_transaksi_simpanan as $simpanan): ?>
                            <?php
                            $tanggal = ($simpanan['tanggal'] && $simpanan['tanggal'] != '0000-00-00') ? date('d-m-Y', strtotime($simpanan['tanggal'])) : '-';
                            $nama_anggota = $simpanan['nama_anggota'] ?? 'Tidak ada nama';
                            $total_simpanan += $simpanan['nominal'];
                            ?>
                            <tr>
                              <td><?= $no++ ?></td>
                              <td><?= $tanggal ?></td>
                              <td><?= htmlspecialchars($nama_anggota) ?></td>
                              <td class="text-right">Rp <?= number_format($simpanan['nominal'] ?? 0, 0, ',', '.') ?></td>
                            </tr>
                          <?php endforeach; ?>
                          <tr class="total-row">
                            <td colspan="3" class="text-right"><strong>Total Simpanan:</strong></td>
                            <td class="text-right"><strong>Rp <?= number_format($total_simpanan, 0, ',', '.') ?></strong></td>
                          </tr>
                        <?php else: ?>
                          <tr>
                            <td colspan="4" class="text-center text-muted py-4">
                              <i class="ti-info-alt" style="font-size: 2rem;"></i><br>
                              Tidak ada data simpanan
                            </td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tabel Detail Pinjaman -->
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Detail Pinjaman ACC</h4>
                    <div class="no-print">
                      <button onclick="printTable()" class="btn btn-primary btn-sm">
                        <i class="ti-printer"></i> Print
                      </button>
                      <button onclick="exportToExcel()" class="btn btn-success btn-sm">
                        <i class="ti-file"></i> Excel
                      </button>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                      <thead class="thead-dark">
                        <tr>
                          <th>No</th>
                          <th>Tanggal Pengajuan</th>
                          <th>Tanggal ACC</th>
                          <th>Nama Anggota</th>
                          <th class="text-right">Jumlah Pinjaman</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (count($data_transaksi_pinjaman) > 0): ?>
                          <?php $no = 1; $total_pinjaman = 0; ?>
                          <?php foreach ($data_transaksi_pinjaman as $pinjaman): ?>
                            <?php
                            $tgl_pengajuan = ($pinjaman['tanggal_pengajuan'] && $pinjaman['tanggal_pengajuan'] != '0000-00-00') ? date('d-m-Y', strtotime($pinjaman['tanggal_pengajuan'])) : '-';
                            $tgl_acc = ($pinjaman['tanggal_acc'] && $pinjaman['tanggal_acc'] != '0000-00-00') ? date('d-m-Y', strtotime($pinjaman['tanggal_acc'])) : '-';
                            $nama_anggota = $pinjaman['nama_anggota'] ?? 'Tidak ada nama';
                            $total_pinjaman += $pinjaman['jumlah_pinjaman'];
                            ?>
                            <tr>
                              <td><?= $no++ ?></td>
                              <td><?= $tgl_pengajuan ?></td>
                              <td><?= $tgl_acc ?></td>
                              <td><?= htmlspecialchars($nama_anggota) ?></td>
                              <td class="text-right">Rp <?= number_format($pinjaman['jumlah_pinjaman'] ?? 0, 0, ',', '.') ?></td>
                              <td>
                                <span class="badge badge-success">
                                  <?= strtoupper($pinjaman['status'] ?? 'pending') ?>
                                </span>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                          <tr class="total-row">
                            <td colspan="4" class="text-right"><strong>Total Pinjaman ACC:</strong></td>
                            <td class="text-right"><strong>Rp <?= number_format($total_pinjaman, 0, ',', '.') ?></strong></td>
                            <td></td>
                          </tr>
                        <?php else: ?>
                          <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                              <i class="ti-info-alt" style="font-size: 2rem;"></i><br>
                              Tidak ada data pinjaman ACC
                            </td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tabel Detail Angsuran -->
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Detail Transaksi Angsuran Lunas</h4>
                    <div class="no-print">
                      <button onclick="printTable()" class="btn btn-primary btn-sm">
                        <i class="ti-printer"></i> Print
                      </button>
                      <button onclick="exportToExcel()" class="btn btn-success btn-sm">
                        <i class="ti-file"></i> Excel
                      </button>
                    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                      <thead class="thead-dark">
                        <tr>
                          <th>No</th>
                          <th>Tanggal Pelunasan</th>
                          <th>Nama Anggota</th>
                          <th>Angsuran Ke</th>
                          <th class="text-right">Nominal</th>
                          <th class="text-right">Bunga</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (count($data_transaksi_angsuran) > 0): ?>
                          <?php $no = 1; $total_angsuran = 0; $total_bunga = 0; ?>
                          <?php foreach ($data_transaksi_angsuran as $angsuran): ?>
                            <?php
                            $tgl_pelunasan = ($angsuran['tgl_pelunasan'] && $angsuran['tgl_pelunasan'] != '0000-00-00') ? date('d-m-Y', strtotime($angsuran['tgl_pelunasan'])) : '-';
                            $nama_anggota = $angsuran['nama_anggota'] ?? 'Tidak ada nama';
                            $total_angsuran += $angsuran['nominal'];
                            $total_bunga += $angsuran['bunga'];
                            ?>
                            <tr>
                              <td><?= $no++ ?></td>
                              <td><?= $tgl_pelunasan ?></td>
                              <td><?= htmlspecialchars($nama_anggota) ?></td>
                              <td><?= $angsuran['angsuran_ke'] ?? 0 ?></td>
                              <td class="text-right">Rp <?= number_format($angsuran['nominal'] ?? 0, 0, ',', '.') ?></td>
                              <td class="text-right">Rp <?= number_format($angsuran['bunga'] ?? 0, 0, ',', '.') ?></td>
                              <td>
                                <span class="badge badge-success">
                                  LUNAS
                                </span>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                          <tr class="total-row">
                            <td colspan="4" class="text-right"><strong>Total Angsuran:</strong></td>
                            <td class="text-right"><strong>Rp <?= number_format($total_angsuran, 0, ',', '.') ?></strong></td>
                            <td class="text-right"><strong>Rp <?= number_format($total_bunga, 0, ',', '.') ?></strong></td>
                            <td></td>
                          </tr>
                        <?php else: ?>
                          <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                              <i class="ti-info-alt" style="font-size: 2rem;"></i><br>
                              Tidak ada data angsuran lunas
                            </td>
                          </tr>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Tabel Detail SHU Per Anggota -->
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="card-title mb-0">Rincian Perolehan SHU Per Anggota</h4>
                    <div id="shuButtons"></div>
                  </div>
                  <p class="text-muted small mb-3">
                    <strong>Penjelasan:</strong> SHU dibagi merata kepada semua anggota dari keuntungan bunga angsuran. Rumus: Total Bunga ÷ Jumlah Anggota
                  </p>
                  <div class="table-responsive">
                    <table id="tabelSHU" class="table table-bordered table-hover table-sm">
                      <thead class="thead-dark">
                        <tr style="background-color: #f8f9fa;">
                          <th style="width: 5%; color: #000;">No</th>
                          <th style="width: 60%; color: #000;">Nama Anggota</th>
                          <th style="width: 35%; color: #000;" class="text-right">Perolehan SHU</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                        $no = 1;
                        $total_shu_semua = 0;
                        foreach($shu_per_anggota as $id => $data): 
                            $total_shu_semua += $data['shu_perolehan'];
                        ?>
                        <tr>
                          <td><?= $no++ ?></td>
                          <td><?= htmlspecialchars($data['nama']) ?></td>
                          <td class="text-right"><strong style="color: #667eea;">Rp <?= number_format($data['shu_perolehan'], 0, ',', '.') ?></strong></td>
                        </tr>
                        <?php endforeach; ?>
                        <tr class="table-info total-row" style="background-color: #e3f2fd;">
                          <td colspan="2" class="text-right"><strong>TOTAL SHU UNTUK SEMUA ANGGOTA:</strong></td>
                          <td class="text-right"><strong style="color: #667eea;">Rp <?= number_format($total_shu_semua, 0, ',', '.') ?></strong></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <footer class="footer no-print" style="background-color: #f8f9fa; border-top: 1px solid #dee2e6; padding: 20px 0; margin-top: 40px;">
            <div class="container-fluid">
              <div class="row">
                <div class="col-md-6">
                  <p class="text-muted mb-0">
                    <strong>Sistem Manajemen Koperasi</strong><br>
                    © 2025 Koperasi Management System. All rights reserved.
                  </p>
                </div>
                <div class="col-md-6 text-right">
                  <p class="text-muted mb-0">
                    Versi 1.0 | <a href="#" class="text-muted">Bantuan</a> | <a href="#" class="text-muted">Kontak</a>
                  </p>
                </div>
              </div>
            </div>
          </footer>
        </div>
      </div>
    </div>

  <!-- JavaScript -->
  <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
  <script src="../template2/vendors/js/vendor.bundle.base.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- DataTables -->
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
  
  <script src="../template2/js/off-canvas.js"></script>
  <script src="../template2/js/hoverable-collapse.js"></script>
  <script src="../template2/js/template.js"></script>

  <script>
    $(document).ready(function() {
        // Initialize DataTable untuk Rekap Keseluruhan
        var table = $('#tabelRekap').DataTable({
            responsive: true,
            paging: false,
            searching: false,
            info: false,
            ordering: false,
            dom: 'Brt',
            buttons: [
                {
                    extend: 'excel',
                    text: '<i class="ti-file"></i> Excel',
                    className: 'btn btn-success btn-sm mb-2',
                    filename: 'Rekap_Koperasi_' + new Date().toISOString().split('T')[0],
                    title: 'REKAP KESELURUHAN KOPERASI',
                    exportOptions: {
                        columns: [0, 1]
                    }
                },
                {
                    extend: 'pdf',
                    text: '<i class="ti-file"></i> PDF',
                    className: 'btn btn-danger btn-sm mb-2',
                    filename: 'Rekap_Koperasi_' + new Date().toISOString().split('T')[0],
                    title: 'REKAP KESELURUHAN KOPERASI',
                    orientation: 'portrait',
                    pageSize: 'A4',
                    exportOptions: {
                        columns: [0, 1]
                    },
                    customize: function(doc) {
                        try {
                            if(doc.content[1] && doc.content[1].table) {
                                doc.content[1].table.headerRows = 1;
                                var headerRow = doc.content[1].table.body[0];
                                if(headerRow) {
                                    for(var i = 0; i < headerRow.length; i++) {
                                        headerRow[i].fillColor = '#667eea';
                                        headerRow[i].textColor = 255;
                                        headerRow[i].alignment = 'center';
                                    }
                                }
                                
                                // Format kolom Total (kolom ke-2) menjadi right-align
                                for(var i = 1; i < doc.content[1].table.body.length; i++) {
                                    doc.content[1].table.body[i][1].alignment = 'right';
                                }
                            }
                            
                            doc.defaultStyle.fontSize = 11;
                            if(doc.styles && doc.styles.tableHeader) {
                                doc.styles.tableHeader.fontSize = 12;
                            }
                            
                            doc.content.splice(0, 0, {
                                text: 'REKAP KESELURUHAN KOPERASI',
                                fontSize: 16,
                                bold: true,
                                alignment: 'center',
                                margin: [0, 0, 0, 15]
                            });
                            
                            doc.content.push({
                                text: 'Tanggal: ' + new Date().toLocaleDateString('id-ID'),
                                fontSize: 11,
                                alignment: 'right',
                                margin: [0, 10, 0, 0]
                            });
                        } catch(e) {
                            console.log('PDF customize error:', e);
                        }
                    }
                }
            ]
        });

        // Move buttons to custom container
        table.buttons().container().appendTo('#rekapButtons');
        
        // Handle SHU table export buttons manually
        try {
            if($('#tabelSHU').length) {
                // Excel export button
                var excelBtn = $('<button class="btn btn-success btn-sm mb-2"><i class="ti-file"></i> Excel</button>');
                excelBtn.click(function() {
                    var table = document.getElementById('tabelSHU');
                    var html = '<html><head><meta charset="utf-8"></head><body>';
                    html += '<h2>RINCIAN PEROLEHAN SHU PER ANGGOTA</h2>';
                    html += '<p>Perhitungan: SHU = Total Bunga Angsuran ÷ Jumlah Anggota</p>';
                    html += table.outerHTML;
                    html += '</body></html>';
                    
                    var url = 'data:application/vnd.ms-excel;charset=utf-8,' + encodeURIComponent(html);
                    var downloadLink = document.createElement("a");
                    downloadLink.href = url;
                    downloadLink.download = "SHU_Per_Anggota_" + new Date().toISOString().split('T')[0] + ".xls";
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);
                });
                
                // PDF export button
                var pdfBtn = $('<button class="btn btn-danger btn-sm mb-2"><i class="ti-file"></i> PDF</button>');
                pdfBtn.click(function() {
                    try {
                        var element = document.getElementById('tabelSHU');
                        var opt = {
                            margin: 10,
                            filename: 'SHU_Per_Anggota_' + new Date().toISOString().split('T')[0] + '.pdf',
                            image: { type: 'jpeg', quality: 0.98 },
                            html2canvas: { scale: 2 },
                            jsPDF: { orientation: 'landscape', unit: 'mm', format: 'a4' }
                        };
                        
                        // Simple table export with pdfmake
                        var docDefinition = {
                            content: [
                                {
                                    text: 'RINCIAN PEROLEHAN SHU PER ANGGOTA',
                                    fontSize: 16,
                                    bold: true,
                                    alignment: 'center',
                                    margin: [0, 0, 0, 10]
                                },
                                {
                                    text: 'Perhitungan: SHU = Total Bunga Angsuran ÷ Jumlah Anggota (Dibagi Merata)',
                                    fontSize: 10,
                                    alignment: 'center',
                                    italics: true,
                                    margin: [0, 0, 0, 15]
                                },
                                {
                                    table: {
                                        headerRows: 1,
                                        body: generatePdfTableData()
                                    },
                                    layout: 'lightHorizontalLines'
                                },
                                {
                                    text: 'Tanggal: ' + new Date().toLocaleDateString('id-ID'),
                                    fontSize: 10,
                                    alignment: 'right',
                                    margin: [0, 15, 0, 0]
                                }
                            ],
                            pageOrientation: 'landscape'
                        };
                        
                        pdfMake.createPDF(docDefinition).download('SHU_Per_Anggota_' + new Date().toISOString().split('T')[0] + '.pdf');
                    } catch(e) {
                        console.log('PDF error:', e);
                        alert('Error generating PDF');
                    }
                });
                
                $('#shuButtons').append(excelBtn).append(pdfBtn);
            }
        } catch(e) {
            console.log('SHU buttons error:', e);
        }
        
        // Helper function to generate PDF table data
        function generatePdfTableData() {
            var rows = [];
            var table = document.getElementById('tabelSHU');
            
            if(!table) return rows;
            
            // Add header
            var headerCells = [];
            table.querySelectorAll('thead th').forEach(function(th) {
                headerCells.push({text: th.textContent, bold: true, fillColor: '#667eea', color: '#FFF'});
            });
            rows.push(headerCells);
            
            // Add body rows
            table.querySelectorAll('tbody tr').forEach(function(tr) {
                var cells = [];
                tr.querySelectorAll('td').forEach(function(td) {
                    cells.push(td.textContent.trim());
                });
                rows.push(cells);
            });
            
            return rows;
        }
    });

    function printTable() {
      window.print();
    }

    function exportToExcel() {
      // Create a simple table export
      const tables = document.querySelectorAll('table');
      let html = '<html><head><meta charset="utf-8"><title>Rekap Koperasi</title></head><body>';
      html += '<h1>Rekap Koperasi - Keseluruhan</h1>';
      
      tables.forEach(table => {
        html += table.outerHTML;
        html += '<br><br>';
      });
      
      html += '</body></html>';
      
      const url = 'data:application/vnd.ms-excel;charset=utf-8,' + encodeURIComponent(html);
      const downloadLink = document.createElement("a");
      downloadLink.href = url;
      downloadLink.download = "rekap_koperasi_keseluruhan.xls";
      document.body.appendChild(downloadLink);
      downloadLink.click();
      document.body.removeChild(downloadLink);
    }
  </script>
</body>
</html>
<?php
// Close database connection
mysqli_close($koneksi);
?>