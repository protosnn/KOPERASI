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
    
    // 5. GET DETAILED TRANSACTION DATA - SIMPANAN (semua data tanpa filter bulan)
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
                  <h4 class="card-title mb-4">Rekap Keseluruhan</h4>
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                      <thead class="thead-dark">
                        <tr>
                          <th>Keterangan</th>
                          <th class="text-right">Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Total Anggota</td>
                          <td class="text-right"><?= number_format($data_rekap['total_anggota'], 0, ',', '.') ?> orang</td>
                        </tr>
                        <tr>
                          <td>Total Simpanan</td>
                          <td class="text-right">Rp <?= number_format($data_rekap['total_simpanan'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                          <td>Pinjaman ACC</td>
                          <td class="text-right">Rp <?= number_format($data_rekap['pinjaman_acc'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                          <td>Angsuran Lunas</td>
                          <td class="text-right">Rp <?= number_format($data_rekap['angsuran_lunas'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                          <td>Bunga Lunas</td>
                          <td class="text-right">Rp <?= number_format($data_rekap['bunga_lunas'], 0, ',', '.') ?></td>
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

        <!-- Footer -->
        <footer class="footer no-print">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">
              Copyright © 2021. Premium Bootstrap admin template from BootstrapDash.
            </span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">
              Hand-crafted & made with <i class="ti-heart text-danger ml-1"></i>
            </span>
          </div>
        </footer>
      </div>
    </div>
  </div>

  <!-- JavaScript -->
  <script src="../template2/vendors/js/vendor.bundle.base.js"></script>
  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="../template2/js/off-canvas.js"></script>
  <script src="../template2/js/hoverable-collapse.js"></script>
  <script src="../template2/js/template.js"></script>

  <script>
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