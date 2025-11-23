<!DOCTYPE html>
<html lang="en">
<?php 
session_start();
include '../koneksi.php';

// Cek jika user sudah login sebagai anggota
if(!isset($_SESSION['status']) || $_SESSION['status'] != "login_anggota") {
    header("location: login_anggota.php?pesan=belum_login");
    exit();
}

// Ambil data anggota dari session
$anggota_id = $_SESSION['id_anggota'];
$nama_anggota = $_SESSION['nama'];

// Initialize variables
$data_rekap = [
    'total_simpanan' => 0,
    'total_pinjaman_acc' => 0,
    'total_angsuran_lunas' => 0,
    'total_bunga_lunas' => 0,
    'total_angsuran_belum_lunas' => 0,
    'sisa_pinjaman' => 0
];

$data_simpanan = [];
$data_pinjaman = [];
$data_angsuran = [];

try {
    // 1. GET TOTAL SIMPANAN ANGGOTA
    $simpanan_query = "SELECT COALESCE(SUM(nominal), 0) as total FROM simpanan WHERE anggota_id = '$anggota_id'";
    $result_simpanan = mysqli_query($koneksi, $simpanan_query);
    if ($result_simpanan) {
        $simpanan_data = mysqli_fetch_assoc($result_simpanan);
        $data_rekap['total_simpanan'] = $simpanan_data['total'];
    }

    // 2. GET TOTAL PINJAMAN ACC ANGGOTA
    $pinjaman_query = "SELECT COALESCE(SUM(jumlah_pinjaman), 0) as total FROM pinjaman WHERE anggota_id = '$anggota_id' AND status = 'acc'";
    $result_pinjaman = mysqli_query($koneksi, $pinjaman_query);
    if ($result_pinjaman) {
        $pinjaman_data = mysqli_fetch_assoc($result_pinjaman);
        $data_rekap['total_pinjaman_acc'] = $pinjaman_data['total'];
    }

    // 3. GET ANGSURAN LUNAS ANGGOTA
    $angsuran_query = "SELECT 
                      COALESCE(SUM(ag.nominal), 0) as angsuran,
                      COALESCE(SUM(ag.bunga), 0) as bunga
                      FROM angsuran ag 
                      JOIN pinjaman p ON ag.pinjaman_id = p.id 
                      WHERE p.anggota_id = '$anggota_id' AND ag.status = 'lunas'";
    
    $result_angsuran = mysqli_query($koneksi, $angsuran_query);
    if ($result_angsuran) {
        $angsuran_data = mysqli_fetch_assoc($result_angsuran);
        $data_rekap['total_angsuran_lunas'] = $angsuran_data['angsuran'];
        $data_rekap['total_bunga_lunas'] = $angsuran_data['bunga'];
    }

    // 4. GET ANGSURAN BELUM LUNAS ANGGOTA
    $angsuran_belum_query = "SELECT COALESCE(SUM(ag.nominal), 0) as total 
                           FROM angsuran ag 
                           JOIN pinjaman p ON ag.pinjaman_id = p.id 
                           WHERE p.anggota_id = '$anggota_id' AND ag.status = 'belum lunas'";
    
    $result_angsuran_belum = mysqli_query($koneksi, $angsuran_belum_query);
    if ($result_angsuran_belum) {
        $angsuran_belum_data = mysqli_fetch_assoc($result_angsuran_belum);
        $data_rekap['total_angsuran_belum_lunas'] = $angsuran_belum_data['total'];
    }

    // Hitung sisa pinjaman
    $data_rekap['sisa_pinjaman'] = $data_rekap['total_pinjaman_acc'] - $data_rekap['total_angsuran_lunas'];

    // 5. GET DETAIL SIMPANAN ANGGOTA
    $detail_simpanan_query = "SELECT 
                            s.id,
                            s.tanggal,
                            js.nama as jenis_simpanan,
                            s.nominal
                            FROM simpanan s
                            LEFT JOIN jenissimpanan js ON s.jenissimpanan_id = js.id
                            WHERE s.anggota_id = '$anggota_id'
                            ORDER BY s.tanggal DESC, s.id DESC";
    
    $result_detail_simpanan = mysqli_query($koneksi, $detail_simpanan_query);
    if ($result_detail_simpanan) {
        while ($row = mysqli_fetch_assoc($result_detail_simpanan)) {
            $data_simpanan[] = $row;
        }
    }

    // 6. GET DETAIL PINJAMAN ANGGOTA
    $detail_pinjaman_query = "SELECT 
                            p.id,
                            p.tanggal_pengajuan,
                            p.tanggal_acc,
                            p.jumlah_pinjaman,
                            p.lama_angsuran,
                            p.status
                            FROM pinjaman p
                            WHERE p.anggota_id = '$anggota_id'
                            ORDER BY p.tanggal_pengajuan DESC, p.id DESC";
    
    $result_detail_pinjaman = mysqli_query($koneksi, $detail_pinjaman_query);
    if ($result_detail_pinjaman) {
        while ($row = mysqli_fetch_assoc($result_detail_pinjaman)) {
            $data_pinjaman[] = $row;
        }
    }

    // 7. GET DETAIL ANGSURAN ANGGOTA
    $detail_angsuran_query = "SELECT 
                            ag.id,
                            p.id as pinjaman_id,
                            ag.angsuran_ke,
                            ag.nominal,
                            ag.bunga,
                            ag.tgl_jatuhtempo,
                            ag.tgl_pelunasan,
                            ag.status
                            FROM angsuran ag
                            JOIN pinjaman p ON ag.pinjaman_id = p.id
                            WHERE p.anggota_id = '$anggota_id'
                            ORDER BY ag.tgl_jatuhtempo DESC, ag.id DESC";
    
    $result_detail_angsuran = mysqli_query($koneksi, $detail_angsuran_query);
    if ($result_detail_angsuran) {
        while ($row = mysqli_fetch_assoc($result_detail_angsuran)) {
            $data_angsuran[] = $row;
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
  <title>Rekap Saya - Koperasi</title>
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
      color: white;
    }
    .badge-warning {
      background-color: #ffab00;
      color: white;
    }
    .badge-danger {
      background-color: #fc424a;
      color: white;
    }
    .badge-info {
      background-color: #3699ff;
      color: white;
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
    <?php 
      $root_path = '../';
      include '../layout/header_anggota.php'; 
    ?>
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

      <!-- sidebar -->
      <?php include '../layout/sidebar_anggota.php'; ?>

      <!-- Main Panel -->
      <div class="main-panel">
        <div class="content-wrapper">
          <!-- Header -->
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="row">
                <div class="col-12 col-xl-12 mb-2 mb-xl-0">
                  <h1 class="font-weight-bold">Rekap Data Saya</h1>
                  <h6 class="font-weight-normal mb-0">Dashboard Rekapitulasi Data <?php echo $nama_anggota; ?></h6>
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
                      <p class="card-title mb-0">Total Simpanan</p>
                      <h3 class="text-success">Rp <?= number_format($data_rekap['total_simpanan'], 0, ',', '.') ?></h3>
                    </div>
                    <div class="icon-box">
                      <i class="ti-wallet text-success" style="font-size: 2rem;"></i>
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
                      <p class="card-title mb-0">Total Pinjaman</p>
                      <h3 class="text-info">Rp <?= number_format($data_rekap['total_pinjaman_acc'], 0, ',', '.') ?></h3>
                    </div>
                    <div class="icon-box">
                      <i class="ti-money text-info" style="font-size: 2rem;"></i>
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
                      <p class="card-title mb-0">Angsuran Dibayar</p>
                      <h3 class="text-warning">Rp <?= number_format($data_rekap['total_angsuran_lunas'], 0, ',', '.') ?></h3>
                    </div>
                    <div class="icon-box">
                      <i class="ti-check-box text-warning" style="font-size: 2rem;"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-md-3 grid-margin stretch-card">
              <div class="card summary-card <?= $data_rekap['sisa_pinjaman'] > 0 ? 'danger' : '' ?>">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <p class="card-title mb-0">Sisa Pinjaman</p>
                      <h3 class="<?= $data_rekap['sisa_pinjaman'] > 0 ? 'text-danger' : 'text-success' ?>">
                        Rp <?= number_format($data_rekap['sisa_pinjaman'], 0, ',', '.') ?>
                      </h3>
                    </div>
                    <div class="icon-box">
                      <i class="ti-receipt <?= $data_rekap['sisa_pinjaman'] > 0 ? 'text-danger' : 'text-success' ?>" style="font-size: 2rem;"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tabel Rekap Pribadi -->
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="card-title mb-0">Rekap Data Saya</h4>
                    <div class="no-print">
                      <button onclick="window.print()" class="btn btn-primary btn-sm">
                        <i class="ti-printer"></i> Print
                      </button>
                    </div>
                  </div>
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
                          <td>Total Simpanan</td>
                          <td class="text-right">Rp <?= number_format($data_rekap['total_simpanan'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                          <td>Total Pinjaman (ACC)</td>
                          <td class="text-right">Rp <?= number_format($data_rekap['total_pinjaman_acc'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                          <td>Angsuran Dibayar (Lunas)</td>
                          <td class="text-right">Rp <?= number_format($data_rekap['total_angsuran_lunas'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                          <td>Bunga Dibayar</td>
                          <td class="text-right">Rp <?= number_format($data_rekap['total_bunga_lunas'], 0, ',', '.') ?></td>
                        </tr>
                        <tr>
                          <td>Angsuran Belum Dibayar</td>
                          <td class="text-right">Rp <?= number_format($data_rekap['total_angsuran_belum_lunas'], 0, ',', '.') ?></td>
                        </tr>
                        <tr class="table-primary total-row">
                          <td><strong>Sisa Pinjaman</strong></td>
                          <td class="text-right"><strong>Rp <?= number_format($data_rekap['sisa_pinjaman'], 0, ',', '.') ?></strong></td>
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
                  <h4 class="card-title mb-4">Detail Simpanan Saya</h4>
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                      <thead class="thead-dark">
                        <tr>
                          <th>No</th>
                          <th>Tanggal</th>
                          <th>Jenis Simpanan</th>
                          <th class="text-right">Nominal</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (count($data_simpanan) > 0): ?>
                          <?php $no = 1; $total_simpanan = 0; ?>
                          <?php foreach ($data_simpanan as $simpanan): ?>
                            <?php
                            $tanggal = ($simpanan['tanggal'] && $simpanan['tanggal'] != '0000-00-00') ? date('d-m-Y', strtotime($simpanan['tanggal'])) : '-';
                            $total_simpanan += $simpanan['nominal'];
                            ?>
                            <tr>
                              <td><?= $no++ ?></td>
                              <td><?= $tanggal ?></td>
                              <td><?= htmlspecialchars($simpanan['jenis_simpanan'] ?? 'Simpanan') ?></td>
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
                  <h4 class="card-title mb-4">Detail Pinjaman Saya</h4>
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                      <thead class="thead-dark">
                        <tr>
                          <th>No</th>
                          <th>Tanggal Pengajuan</th>
                          <th>Tanggal ACC</th>
                          <th class="text-right">Jumlah Pinjaman</th>
                          <th>Lama Angsuran</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (count($data_pinjaman) > 0): ?>
                          <?php $no = 1; $total_pinjaman = 0; ?>
                          <?php foreach ($data_pinjaman as $pinjaman): ?>
                            <?php
                            $tgl_pengajuan = ($pinjaman['tanggal_pengajuan'] && $pinjaman['tanggal_pengajuan'] != '0000-00-00') ? date('d-m-Y', strtotime($pinjaman['tanggal_pengajuan'])) : '-';
                            $tgl_acc = ($pinjaman['tanggal_acc'] && $pinjaman['tanggal_acc'] != '0000-00-00') ? date('d-m-Y', strtotime($pinjaman['tanggal_acc'])) : '-';
                            $total_pinjaman += $pinjaman['jumlah_pinjaman'];
                            
                            $status_badge = '';
                            $status_text = '';
                            if($pinjaman['status'] == 'acc') {
                                $status_badge = 'badge-success';
                                $status_text = 'DISETUJUI';
                            } elseif($pinjaman['status'] == 'pending') {
                                $status_badge = 'badge-warning';
                                $status_text = 'MENUNGGU';
                            } else {
                                $status_badge = 'badge-danger';
                                $status_text = 'DITOLAK';
                            }
                            ?>
                            <tr>
                              <td><?= $no++ ?></td>
                              <td><?= $tgl_pengajuan ?></td>
                              <td><?= $tgl_acc ?></td>
                              <td class="text-right">Rp <?= number_format($pinjaman['jumlah_pinjaman'] ?? 0, 0, ',', '.') ?></td>
                              <td><?= $pinjaman['lama_angsuran'] ?? 0 ?> bulan</td>
                              <td>
                                <span class="badge <?= $status_badge ?>">
                                  <?= $status_text ?>
                                </span>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                          <tr class="total-row">
                            <td colspan="3" class="text-right"><strong>Total Pinjaman:</strong></td>
                            <td class="text-right"><strong>Rp <?= number_format($total_pinjaman, 0, ',', '.') ?></strong></td>
                            <td colspan="2"></td>
                          </tr>
                        <?php else: ?>
                          <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                              <i class="ti-info-alt" style="font-size: 2rem;"></i><br>
                              Tidak ada data pinjaman
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
                  <h4 class="card-title mb-4">Detail Angsuran Saya</h4>
                  <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                      <thead class="thead-dark">
                        <tr>
                          <th>No</th>
                          <th>Pinjaman ID</th>
                          <th>Angsuran Ke</th>
                          <th class="text-right">Nominal</th>
                          <th class="text-right">Bunga</th>
                          <th>Jatuh Tempo</th>
                          <th>Tanggal Bayar</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if (count($data_angsuran) > 0): ?>
                          <?php $no = 1; $total_angsuran = 0; $total_bunga = 0; ?>
                          <?php foreach ($data_angsuran as $angsuran): ?>
                            <?php
                            $tgl_jatuhtempo = ($angsuran['tgl_jatuhtempo'] && $angsuran['tgl_jatuhtempo'] != '0000-00-00') ? date('d-m-Y', strtotime($angsuran['tgl_jatuhtempo'])) : '-';
                            $tgl_pelunasan = ($angsuran['tgl_pelunasan'] && $angsuran['tgl_pelunasan'] != '0000-00-00') ? date('d-m-Y', strtotime($angsuran['tgl_pelunasan'])) : '-';
                            $total_angsuran += $angsuran['nominal'];
                            $total_bunga += $angsuran['bunga'];
                            
                            $status_badge = ($angsuran['status'] == 'lunas') ? 'badge-success' : 'badge-warning';
                            $status_text = ($angsuran['status'] == 'lunas') ? 'LUNAS' : 'BELUM LUNAS';
                            ?>
                            <tr>
                              <td><?= $no++ ?></td>
                              <td>#<?= $angsuran['pinjaman_id'] ?></td>
                              <td><?= $angsuran['angsuran_ke'] ?? 0 ?></td>
                              <td class="text-right">Rp <?= number_format($angsuran['nominal'] ?? 0, 0, ',', '.') ?></td>
                              <td class="text-right">Rp <?= number_format($angsuran['bunga'] ?? 0, 0, ',', '.') ?></td>
                              <td><?= $tgl_jatuhtempo ?></td>
                              <td><?= $tgl_pelunasan ?></td>
                              <td>
                                <span class="badge <?= $status_badge ?>">
                                  <?= $status_text ?>
                                </span>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                          <tr class="total-row">
                            <td colspan="3" class="text-right"><strong>Total:</strong></td>
                            <td class="text-right"><strong>Rp <?= number_format($total_angsuran, 0, ',', '.') ?></strong></td>
                            <td class="text-right"><strong>Rp <?= number_format($total_bunga, 0, ',', '.') ?></strong></td>
                            <td colspan="3"></td>
                          </tr>
                        <?php else: ?>
                          <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                              <i class="ti-info-alt" style="font-size: 2rem;"></i><br>
                              Tidak ada data angsuran
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
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2025. Koperasi Management System.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Rekap Data Anggota</span>
          </div>
        </footer>
      </div>
    </div>
  </div>

  <!-- plugins:js -->
  <script src="../template2/vendors/js/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="../template2/vendors/chart.js/Chart.min.js"></script>
  <script src="../template2/vendors/datatables.net/jquery.dataTables.js"></script>
  <script src="../template2/vendors/datatables.net-bs4/dataTables.bootstrap4.js"></script>
  <script src="../template2/js/dataTables.select.min.js"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="../template2/js/off-canvas.js"></script>
  <script src="../template2/js/hoverable-collapse.js"></script>
  <script src="../template2/js/template.js"></script>
  <script src="../template2/js/settings.js"></script>
  <script src="../template2/js/todolist.js"></script>
  <!-- endinject -->
</body>
</html>
<?php
// Close database connection
mysqli_close($koneksi);
?>